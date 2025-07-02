<?php

namespace App\Http\Controllers;

use App\Models\ItemUnit;
use App\Models\BorrowRequest;
use App\Models\ReturnRequest;
use App\Models\ReturnDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ReturnRequestsExport;

class ReturnRequestController extends Controller
{
    const PAGINATION_COUNT = 10;
    const EXPORT_FILENAME = 'data_pengembalian';

    public function index(Request $request)
    {
        $query = ReturnRequest::with(['user', 'handler', 'borrowRequest.borrowDetails.itemUnit.item']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', "%$search%")
                      ->orWhere('username', 'like', "%$search%");
                });
            });
        }

        $requests = $query->orderBy('created_at', 'desc')->paginate(self::PAGINATION_COUNT);

        if ($request->ajax()) {
            return response()->json([
                'status' => 'success',
                'data' => $requests->items(),
                'links' => $requests->links()->toHtml(),
            ], 200);
        }

        return view('return_requests.index', compact('requests'));
    }

    public function apiIndex(Request $request)
    {
        $query = ReturnRequest::with(['user', 'handler', 'borrowRequest.borrowDetails.itemUnit.item'])
            ->where('status', $request->status ?? 'pending');

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $requests = $query->orderBy('created_at', 'desc')->paginate(self::PAGINATION_COUNT);

        return response()->json([
            'status' => 'success',
            'data' => $requests,
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'borrow_request_id' => 'required|exists:borrow_requests,id',
            'notes' => 'nullable|string|max:1000',
            'items' => 'required|array|min:1',
            'items.*.item_unit_id' => 'required|exists:item_units,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.condition' => 'required|string|in:good,damaged,broken',
            'items.*.photo' => 'required|file|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()], 422);
        }

        $validated = $validator->validated();

        $borrowRequest = BorrowRequest::findOrFail($validated['borrow_request_id']);
        if ($borrowRequest->status !== 'approved') {
            return response()->json(['status' => 'error', 'message' => 'Hanya peminjaman yang disetujui dapat dikembalikan.'], 400);
        }

        DB::beginTransaction();
        try {
            $returnRequest = ReturnRequest::create([
                'borrow_request_id' => $validated['borrow_request_id'],
                'user_id' => Auth::id(),
                'handle_by' => null,
                'status' => 'pending',
                'notes' => $validated['notes'] ?? null,
            ]);

            foreach ($validated['items'] as $item) {
                $itemUnit = ItemUnit::findOrFail($item['item_unit_id']);
                $photoPath = $item['photo']->store('return_photos', 'public');

                ReturnDetail::create([
                    'return_request_id' => $returnRequest->id,
                    'item_unit_id' => $item['item_unit_id'],
                    'quantity' => $item['quantity'],
                    'condition' => $item['condition'],
                    'photo' => $photoPath,
                    'notes' => $item['notes'] ?? null,
                ]);
            }

            DB::commit();

            return response()->json(['status' => 'success', 'message' => 'Permintaan pengembalian berhasil dibuat!'], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => 'Gagal membuat permintaan pengembalian: ' . $e->getMessage()], 500);
        }
    }

    public function getUserReturnHistory(Request $request)
    {
        $userId = Auth::id();
        $query = ReturnRequest::where('user_id', $userId);

        if ($request->has('status')) {
            $query->where('status', $request->input('status'));
        }

        $returnRequests = $query->with('borrowRequest.borrowDetails.itemUnit.item')->get();

        return response()->json([
            'status' => 'success',
            'data' => $returnRequests,
        ], 200);
    }

    public function show(ReturnRequest $returnRequest)
    {
        $returnRequest->load(['user', 'handler', 'borrowRequest.borrowDetails.itemUnit.item', 'returnDetails']);
        return view('return_requests.show', compact('returnRequest'));
    }

    public function apiShow($id)
    {
        $returnRequest = ReturnRequest::with(['user', 'handler', 'borrowRequest.borrowDetails.itemUnit.item', 'returnDetails'])->findOrFail($id);
        return response()->json([
            'status' => 'success',
            'data' => $returnRequest,
        ], 200);
    }

    public function approve(Request $request, ReturnRequest $returnRequest)
    {
        if ($returnRequest->status !== 'pending') {
            return response()->json(['status' => 'error', 'message' => 'Hanya permintaan pending yang dapat disetujui.'], 400);
        }

        DB::beginTransaction();
        try {
            $returnRequest->update([
                'status' => 'approved',
                'handle_by' => Auth::id(),
            ]);

            $borrowRequest = $returnRequest->borrowRequest;
            $borrowRequest->update(['status' => 'returned']);

            foreach ($returnRequest->returnDetails as $detail) {
                $itemUnit = $detail->itemUnit;
                if ($itemUnit->item->type === 'consumable') {
                    $itemUnit->quantity += $detail->quantity;
                } else {
                    $itemUnit->status = 'available';
                }
                $itemUnit->condition = $detail->condition;
                $itemUnit->save();
            }

            DB::commit();
            return response()->json(['status' => 'success', 'message' => 'Permintaan pengembalian disetujui.'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => 'Gagal menyetujui permintaan: ' . $e->getMessage()], 500);
        }
    }

    public function reject(Request $request, ReturnRequest $returnRequest)
    {
        $validator = Validator::make($request->all(), [
            'rejection_reason' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()], 422);
        }

        if ($returnRequest->status !== 'pending') {
            return response()->json(['status' => 'error', 'message' => 'Hanya permintaan pending yang dapat ditolak.'], 400);
        }

        DB::beginTransaction();
        try {
            $returnRequest->update([
                'status' => 'rejected',
                'handle_by' => Auth::id(),
                'rejection_reason' => $request->rejection_reason,
            ]);

            DB::commit();
            return response()->json(['status' => 'success', 'message' => 'Permintaan pengembalian ditolak.'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => 'Gagal menolak permintaan: ' . $e->getMessage()], 500);
        }
    }

    public function exportExcel()
    {
        try {
            $filename = self::EXPORT_FILENAME . '-' . now()->format('Y-m-d') . '.xlsx';
            return Excel::download(new ReturnRequestsExport, $filename);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengekspor Excel: ' . $e->getMessage());
        }
    }

    public function exportPdf()
    {
        try {
            $returnRequests = ReturnRequest::with(['user', 'handler', 'borrowRequest.borrowDetails.itemUnit.item', 'returnDetails'])->get();
            $pdf = Pdf::loadView('return_requests.pdf', compact('returnRequests'))
                ->setPaper('a4', 'landscape');
            $filename = self::EXPORT_FILENAME . '-' . now()->format('Y-m-d') . '.pdf';
            return $pdf->download($filename);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengekspor PDF: ' . $e->getMessage());
        }
    }
}

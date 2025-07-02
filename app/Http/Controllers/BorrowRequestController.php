<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ItemUnit;
use App\Models\BorrowDetail;
use App\Models\BorrowRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\BorrowRequestsExport;

class BorrowRequestController extends Controller
{
    const PAGINATION_COUNT = 10;
    const EXPORT_FILENAME = 'data_peminjaman';

    public function index(Request $request)
    {
        $query = BorrowRequest::with(['user', 'handler', 'borrowDetails.itemUnit.item']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('start_date')) {
            $query->whereDate('borrow_date_expected', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('borrow_date_expected', '<=', $request->end_date);
        }
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $requests = $query->orderBy('created_at', 'desc')->paginate(self::PAGINATION_COUNT);

        if ($request->ajax()) {
            return response()->json([
                'data' => $requests->items(),
                'links' => $requests->links()->toHtml(),
            ]);
        }

        return view('borrow_requests.index', compact('requests'));
    }

    public function apiIndex(Request $request)
    {
        $query = BorrowRequest::with(['user', 'handler', 'borrowDetails.itemUnit.item'])
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
    if (!Auth::check()) {
        return response()->json(['status' => 'error', 'message' => 'User not authenticated'], 401);
    }

    $validator = Validator::make($request->all(), [
        'borrow_date_expected' => 'required|date|after_or_equal:' . now()->format('Y-m-d'),
        'return_date_expected' => 'required|date|after:borrow_date_expected',
        'reason' => 'required|string|max:255',
        'notes' => 'nullable|string|max:1000',
        'items' => 'required|array|min:1',
        'items.*.item_unit_id' => 'required|exists:item_units,id',
        'items.*.quantity' => 'required|integer|min:1',
    ]);

    if ($validator->fails()) {
        return response()->json(['status' => 'error', 'errors' => $validator->errors()], 422);
    }

    $validated = $validator->validated();
    $userId = Auth::id();

    DB::beginTransaction();
    try {
        $borrowRequest = BorrowRequest::create([
            'borrow_date_expected' => $validated['borrow_date_expected'],
            'return_date_expected' => $validated['return_date_expected'],
            'reason' => $validated['reason'],
            'notes' => $validated['notes'] ?? null,
            'user_id' => $userId, // Pastikan user_id diisi
            'status' => 'pending',
        ]);

        foreach ($validated['items'] as $item) {
            $itemUnit = ItemUnit::findOrFail($item['item_unit_id']);
            if ($itemUnit->status !== 'available' || ($itemUnit->quantity < $item['quantity'] && $itemUnit->item->type === 'consumable')) {
                return response()->json(['status' => 'error', 'message' => 'Item tidak tersedia atau kuantitas melebihi stok.'], 400);
            }

            BorrowDetail::create([
                'borrow_request_id' => $borrowRequest->id,
                'item_unit_id' => $item['item_unit_id'],
                'quantity' => $item['quantity'],
            ]);

            if ($itemUnit->item->type === 'consumable') {
                $itemUnit->quantity -= $item['quantity'];
            } else {
                $itemUnit->status = 'borrowed';
            }
            $itemUnit->save();
        }

        DB::commit();

        // Pastikan data yang dikembalikan konsisten
        $borrowRequest->load('borrowDetails.itemUnit.item'); // Load relasi untuk konsistensi
        return response()->json([
            'status' => 'success',
            'message' => 'Permintaan peminjaman berhasil dibuat!',
            'data' => $borrowRequest->toArray(),
        ], 201);
    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json(['status' => 'error', 'message' => 'Gagal membuat permintaan peminjaman: ' . $e->getMessage()], 500);
    }
}

    public function getUserBorrowHistory(Request $request)
    {
        $userId = Auth::id();
        $query = BorrowRequest::where('user_id', $userId);

        if ($request->has('status')) {
            $query->where('status', $request->input('status'));
        }

        $borrowRequests = $query->with('borrowDetails.itemUnit.item')->get();

        return response()->json([
            'status' => 'success',
            'data' => $borrowRequests,
        ], 200);
    }

    public function show(BorrowRequest $borrowRequest)
    {
        $borrowRequest->load(['user', 'handler', 'borrowDetails.itemUnit.item']);
        return view('borrow_requests.show', compact('borrowRequest'));
    }

    public function apiShow($id)
    {
        $borrowRequest = BorrowRequest::with(['user', 'handler', 'borrowDetails.itemUnit.item'])->findOrFail($id);
        return response()->json([
            'status' => 'success',
            'data' => $borrowRequest,
        ], 200);
    }

    public function approve(Request $request, BorrowRequest $borrowRequest)
    {
        if ($borrowRequest->status !== 'pending') {
            return response()->json(['status' => 'error', 'message' => 'Hanya permintaan pending yang dapat disetujui.'], 400);
        }

        DB::beginTransaction();
        try {
            $borrowRequest->update([
                'status' => 'approved',
                'handle_by' => Auth::id(),
            ]);

            DB::commit();
            return response()->json(['status' => 'success', 'message' => 'Permintaan peminjaman disetujui.'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function reject(Request $request, BorrowRequest $borrowRequest)
    {
        $validator = Validator::make($request->all(), [
            'rejection_reason' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()], 422);
        }

        if ($borrowRequest->status !== 'pending') {
            return response()->json(['status' => 'error', 'message' => 'Hanya permintaan pending yang dapat ditolak.'], 400);
        }

        DB::beginTransaction();
        try {
            $borrowRequest->update([
                'status' => 'rejected',
                'handle_by' => Auth::id(),
                'rejection_reason' => $request->rejection_reason,
            ]);

            foreach ($borrowRequest->borrowDetails as $detail) {
                $itemUnit = $detail->itemUnit;
                if ($itemUnit->item->type === 'consumable') {
                    $itemUnit->quantity += $detail->quantity;
                } else {
                    $itemUnit->status = 'available';
                }
                $itemUnit->save();
            }

            DB::commit();
            return response()->json(['status' => 'success', 'message' => 'Permintaan peminjaman ditolak.'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

public function getActiveBorrows(Request $request)
{
    $userId = $request->filled('user_id') ? $request->user_id : Auth::id();
    if (!$userId) {
        return response()->json(['success' => false, 'message' => 'User not authenticated'], 401);
    }

    $activeBorrows = BorrowRequest::where('user_id', $userId)
        ->whereIn('status', ['pending', 'approved'])
        ->with('borrowDetails.itemUnit.item')
        ->get();

    return response()->json([
        'success' => true,
        'message' => 'Active borrows retrieved successfully',
        'data' => $activeBorrows->isEmpty() ? [] : $activeBorrows->toArray(),
        'current_page' => 1, // Default untuk kompatibilitas dengan PaginateResponse
        'per_page' => $activeBorrows->count(), // Jumlah item per halaman
    ], 200);
}

    public function exportExcel()
    {
        $filename = self::EXPORT_FILENAME . '-' . now()->format('Y-m-d') . '.xlsx';
        return Excel::download(new BorrowRequestsExport, $filename);
    }

    public function exportPdf()
    {
        $borrowRequests = BorrowRequest::with(['user', 'handler', 'borrowDetails.itemUnit.item'])->get();
        $pdf = Pdf::loadView('borrow_requests.pdf', compact('borrowRequests'))
            ->setPaper('a4', 'landscape');
        $filename = self::EXPORT_FILENAME . '-' . now()->format('Y-m-d') . '.pdf';
        return $pdf->download($filename);
    }
}

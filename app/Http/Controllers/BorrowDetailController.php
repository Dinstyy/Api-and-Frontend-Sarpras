<?php

namespace App\Http\Controllers;

use App\Models\BorrowDetail;
use App\Models\BorrowRequest;
use App\Models\ItemUnit;
use Illuminate\Http\Request;

class BorrowDetailController extends Controller
{
    public function create($borrowRequestId)
    {
        $borrowRequest = BorrowRequest::findOrFail($borrowRequestId);
        $itemUnits = ItemUnit::with('item')
            ->where('status', 'available')
            ->where(function ($query) {
                $query->whereHas('item', fn($q) => $q->where('type', 'non-consumable'))
                    ->orWhereHas('item', fn($q) => $q->where('type', 'consumable')->where('quantity', '>', 0));
            })
            ->get();

        return view('borrow_details.create', compact('borrowRequest', 'itemUnits'));
    }

public function store(Request $request)
{
    $validated = $request->validate([
        'borrow_request_id' => 'required|exists:borrow_requests,id',
        'item_unit_id' => 'required|exists:item_units,id',
        'quantity' => 'required|integer|min:1',
    ]);

    $itemUnit = ItemUnit::findOrFail($validated['item_unit_id']);

    // Validasi ketersediaan
    if ($itemUnit->item->type === 'consumable' && $itemUnit->quantity < $validated['quantity']) {
        return back()->with('error', 'Stok tidak mencukupi untuk item: ' . $itemUnit->item->name);
    }
    if ($itemUnit->item->type === 'non-consumable' && $itemUnit->status !== 'available') {
        return back()->with('error', 'Item tidak tersedia untuk dipinjam.');
    }

    BorrowDetail::create($validated);

    return redirect()->route('borrow-requests.show', $validated['borrow_request_id'])
        ->with('success', 'Item berhasil ditambahkan ke peminjaman.');
}

    public function destroy(BorrowDetail $borrowDetail)
    {
        $borrowRequestId = $borrowDetail->borrow_request_id;
        $borrowDetail->delete();
        return redirect()->route('borrow-requests.show', $borrowRequestId)
            ->with('success', 'Item berhasil dihapus dari peminjaman.');
    }
}

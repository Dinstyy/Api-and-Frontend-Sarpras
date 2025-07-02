<?php

namespace App\Http\Controllers;

use App\Models\ItemUnit;
use App\Models\ReturnDetail;
use App\Models\ReturnRequest;
use Illuminate\Http\Request;

class ReturnDetailController extends Controller
{
    public function index()
    {
        // Implementation pending
    }

    public function create($returnRequestId)
    {
        $returnRequest = ReturnRequest::findOrFail($returnRequestId);
        $itemUnits = $this->getReturnableItemUnits();

        return view('return_details.create', [
            'returnRequest' => $returnRequest,
            'itemUnits' => $itemUnits
        ]);
    }

    public function store(Request $request)
    {
        $validated = $this->validateReturnDetailRequest($request);
        $detail = ReturnDetail::create($validated);

        return response()->json([
            'message' => 'Return detail added successfully.',
            'data' => $detail,
        ]);
    }

    public function show(string $id)
    {
        // Implementation pending
    }

    public function edit(string $id)
    {
        // Implementation pending
    }

    public function update(Request $request, string $id)
    {
        // Implementation pending
    }

public function destroy(string $id)
{
    $detail = ReturnDetail::findOrFail($id);
    $detail->delete();
    return redirect()->back()->with('success', 'Detail pengembalian berhasil dihapus.');
}

    private function getReturnableItemUnits()
    {
        return ItemUnit::with('item')
            ->where('status', 'borrowed')
            ->get();
    }

    private function validateReturnDetailRequest(Request $request)
    {
        return $request->validate([
            'condition' => 'required|string',
            'item_unit_id' => 'required|exists:item_units,id',
            'return_request_id' => 'required|exists:return_requests,id',
        ]);
    }
}

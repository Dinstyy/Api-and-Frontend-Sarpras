<?php

namespace App\Http\Controllers;

use App\Models\ItemUnit;
use Illuminate\Http\Request;
use App\Models\StockMovement;

class StockMovementController extends Controller
{
    const PAGINATION_COUNT = 10;
    const MOVEMENT_TYPES = ['in', 'out', 'damaged'];
    const DEFAULT_SORT = 'item_asc';

    public function index(Request $request)
    {
        $query = $this->buildFilterQuery($request);
        $movements = $query->paginate(self::PAGINATION_COUNT);

        if ($request->ajax()) {
            return $this->getAjaxResponse($movements);
        }

        return view('stock_movements.index', [
            'movements' => $movements,
            'itemUnits' => ItemUnit::with('item')->get()
        ]);
    }

    public function store(Request $request)
    {
        $validated = $this->validateStockMovementRequest($request);
        $this->processStockMovement($validated);

        return redirect()->back()
            ->with('success', 'Stock movement recorded');
    }

    private function buildFilterQuery(Request $request)
    {
        $query = StockMovement::with(['itemUnit.item'])
            ->latest('movement_date');

        $this->applyFilters($query, $request);
        $this->applySorting($query, $request);

        return $query;
    }

private function applyFilters($query, Request $request)
{
    if ($request->filled('type')) {
        $query->where('stock_movements.type', $request->type);
    }

    if ($request->filled('start_date')) {
        $query->where('stock_movements.movement_date', '>=', $request->start_date);
    }

    if ($request->filled('end_date')) {
        $query->where('stock_movements.movement_date', '<=', $request->end_date);
    }

    if ($request->filled('search')) {
        $query->whereHas('itemUnit.item', function ($q) use ($request) {
            $q->where('name', 'like', '%' . $request->search . '%');
        });
    }
}

    private function applySorting($query, Request $request)
    {
        $sortOptions = [
            'item_asc' => ['items.name', 'asc'],
            'item_desc' => ['items.name', 'desc']
        ];

        $sort = $request->get('sort', self::DEFAULT_SORT);
        $sortOption = $sortOptions[$sort] ?? $sortOptions[self::DEFAULT_SORT];

        if (array_key_exists($sort, $sortOptions)) {
            $query->join('item_units', 'stock_movements.item_unit_id', '=', 'item_units.id')
                ->join('items', 'item_units.item_id', '=', 'items.id')
                ->select('stock_movements.*')
                ->orderBy($sortOption[0], $sortOption[1]);
        } else {
            $query->orderBy($sortOption[0], $sortOption[1]);
        }
    }

    private function getAjaxResponse($movements)
    {
        return response()->json([
            'data' => $movements->items(),
            'current_page' => $movements->currentPage(),
            'last_page' => $movements->lastPage(),
            'from' => $movements->firstItem(),
            'to' => $movements->lastItem(),
            'total' => $movements->total(),
            'links' => $movements->links()->elements,
        ]);
    }

    private function validateStockMovementRequest(Request $request)
    {
        return $request->validate([
            'item_unit_id' => 'required|exists:item_units,id',
            'type' => 'required|in:' . implode(',', self::MOVEMENT_TYPES),
            'quantity' => 'required|integer|min:1',
            'description' => 'nullable|string',
            'movement_date' => 'required|date',
        ]);
    }

    private function processStockMovement(array $data)
    {
        $movement = StockMovement::create($data);
        $itemUnit = $movement->itemUnit;

        match ($movement->type) {
            'in' => $itemUnit->quantity += $movement->quantity,
            'out', 'damaged' => $itemUnit->quantity -= $movement->quantity,
        };

        $itemUnit->save();
    }
}

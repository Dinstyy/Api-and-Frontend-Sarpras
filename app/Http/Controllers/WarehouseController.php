<?php

namespace App\Http\Controllers;

use App\Custom\Formatter;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class WarehouseController extends Controller
{
    // API Methods
    public function index(): JsonResponse
    {
        $warehouseQuery = Warehouse::query();

        $validColumns = [
            'id', 'name', 'location', 'capacity'
        ];

        $validRelation = [];

        if (\request()->filled("with")) {
            $relations = explode(",", trim(\request()->with));
            foreach ($relations as $relation) {
                if (in_array($relation, $validRelation)) {
                    $warehouseQuery = $warehouseQuery->with($relation);
                }
            }
        }

        if (request()->filled('search')) {
            $searchTerm = '%' . request()->search . '%';
            $warehouseQuery->where(function ($query) use ($searchTerm) {
                $query->where('name', 'LIKE', $searchTerm)
                    ->orWhere('location', 'LIKE', $searchTerm)
                    ->orWhere('capacity', 'LIKE', $searchTerm);
            });
        }

        foreach (request()->except(['page', 'size', 'sortBy', 'sortDir', 'search', "with"]) as $key => $value) {
            if (in_array($key, $validColumns) && !empty($value)) {
                $warehouseQuery->where($key, $value);
            }
        }

        $sortBy = in_array(request()->sortBy, $validColumns) ? request()->sortBy : 'created_at';
        $sortDir = strtolower(request()->sortDir) === 'desc' ? 'DESC' : 'ASC';
        $warehouseQuery->orderBy($sortBy, $sortDir);

        $size = min(max(request()->size ?? 10, 1), 100);
        $warehouses = $warehouseQuery->simplePaginate($size);

        return Formatter::apiResponse(200, 'Warehouse list retrieved', $warehouses);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "name" => "required|string|min:3",
            "location" => "required|string",
            "capacity" => "required|integer"
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return Formatter::apiResponse(422, "Validation failed", null, $validator->errors()->all());
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();
        $newWarehouse = Warehouse::query()->create($validated);

        if ($request->expectsJson()) {
            return Formatter::apiResponse(200, "Warehouse created", $newWarehouse);
        }
        return redirect()->route('warehouses.viewIndex')->with('success', 'Warehouse created successfully');
    }

    public function showPublic($id): JsonResponse
{
    $warehouse = Warehouse::with(['itemUnits.item'])->find($id);

    if (!$warehouse) {
        return Formatter::apiResponse(404, 'Warehouse not found');
    }

    $usedCapacity = $warehouse->itemUnits->sum('quantity');
    $remainingCapacity = $warehouse->capacity - $usedCapacity;

    return Formatter::apiResponse(200, 'Warehouse found', [
        'warehouse' => $warehouse,
        'used_capacity' => $usedCapacity,
        'remaining_capacity' => $remainingCapacity,
    ]);
}

public function indexPublic(): JsonResponse
{
    $warehouseQuery = Warehouse::query();

    $validColumns = ['id', 'name', 'location', 'capacity'];

    if (request()->filled('search')) {
        $searchTerm = '%' . request()->search . '%';
        $warehouseQuery->where(function ($query) use ($searchTerm) {
            $query->where('name', 'LIKE', $searchTerm)
                ->orWhere('location', 'LIKE', $searchTerm)
                ->orWhere('capacity', 'LIKE', $searchTerm);
        });
    }

    foreach (request()->except(['page', 'size', 'sortBy', 'sortDir', 'search']) as $key => $value) {
        if (in_array($key, $validColumns) && !empty($value)) {
            $warehouseQuery->where($key, $value);
        }
    }

    $sortBy = in_array(request()->sortBy, $validColumns) ? request()->sortBy : 'created_at';
    $sortDir = strtolower(request()->sortDir) === 'desc' ? 'DESC' : 'ASC';
    $warehouseQuery->orderBy($sortBy, $sortDir);

    $size = min(max(request()->size ?? 10, 1), 100);
    $warehouses = $warehouseQuery->simplePaginate($size);

    return Formatter::apiResponse(200, 'Public warehouse list retrieved', $warehouses);
}

public function show($id)
{
    $warehouse = Warehouse::query()->with(['itemUnits' => function ($query) {
        $query->with('item'); // Ambil data item termasuk image
    }])->find($id);

    if (is_null($warehouse)) {
        if (request()->expectsJson()) {
            return Formatter::apiResponse(404, "Warehouse not found");
        }
        abort(404, 'Warehouse not found');
    }

    $usedCapacity = $warehouse->itemUnits->sum('quantity');
    $remainingCapacity = $warehouse->capacity - $usedCapacity;
    $capacityPercentage = $warehouse->capacity > 0 ? ($usedCapacity / $warehouse->capacity) * 100 : 0;
    $remainingPercentage = $warehouse->capacity > 0 ? ($remainingCapacity / $warehouse->capacity) * 100 : 0;

    if (request()->expectsJson()) {
        return Formatter::apiResponse(200, "Warehouse found", [
            'warehouse' => $warehouse,
            'used_capacity' => $usedCapacity,
            'remaining_capacity' => $remainingCapacity,
            'capacity_percentage' => $capacityPercentage,
            'remaining_percentage' => $remainingPercentage,
        ]);
    }

    return view('warehouses.show', compact('warehouse', 'usedCapacity', 'remainingCapacity', 'capacityPercentage', 'remainingPercentage'));
}

    public function update(Request $request, int $id)
    {
        $warehouse = Warehouse::query()->find($id);
        if (is_null($warehouse)) {
            if ($request->expectsJson()) {
                return Formatter::apiResponse(404, "Warehouse not found");
            }
            return redirect()->route('warehouses.viewIndex')->with('error', 'Warehouse not found');
        }

        $validator = Validator::make($request->all(), [
            "name" => "sometimes|string|min:3",
            "location" => "sometimes|string",
            "capacity" => "sometimes|integer"
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return Formatter::apiResponse(422, "Validation failed", null, $validator->errors()->all());
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();
        $warehouse->update($validated);

        if ($request->expectsJson()) {
            return Formatter::apiResponse(200, "Warehouse updated", Warehouse::query()->find($warehouse->id));
        }
        return redirect()->route('warehouses.viewIndex')->with('success', 'Warehouse updated successfully');
    }

    public function destroy(int $id)
    {
        $warehouse = Warehouse::find($id);
        if (is_null($warehouse)) {
            if (request()->expectsJson()) {
                return Formatter::apiResponse(404, "Warehouse not found");
            }
            return redirect()->route('warehouses.viewIndex')->with('error', 'Warehouse not found');
        }
        $warehouse->forceDelete(); // Changed from delete() to forceDelete() for permanent deletion

        if (request()->expectsJson()) {
            return Formatter::apiResponse(200, "Warehouse deleted permanently");
        }
        return redirect()->route('warehouses.viewIndex')->with('success', 'Warehouse deleted permanently');
    }

    // Web Methods
    public function viewIndex()
    {
        $warehouses = Warehouse::query()->paginate(10);
        return view('warehouses.index', compact('warehouses'));
    }

    public function create()
    {
        return view('warehouses.create');
    }

    public function edit($id)
    {
        $warehouse = Warehouse::find($id);
        if (is_null($warehouse)) {
            return redirect()->route('warehouses.viewIndex')->with('error', 'Warehouse not found');
        }
        return view('warehouses.edit', compact('warehouse'));
    }
}

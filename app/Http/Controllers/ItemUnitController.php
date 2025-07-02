<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\ItemUnit;
use App\Custom\Formatter;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use App\Models\BorrowRequest;
use App\Imports\ItemUnitImport;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\ItemUnitsExport;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ItemUnitController extends Controller
{
    public function index(): JsonResponse
    {
        $itemUnitQuery = ItemUnit::query();
        $validColumns = [
            'unit_code', 'merk', 'condition', 'diperoleh_dari',
            'diperoleh_tanggal', 'status', 'quantity',
            'item_id', 'warehouse_id', 'current_location', 'created_at'
        ];
        $validRelation = ["item", "warehouse"];

        if (\request()->filled("with")) {
            $relations = explode(",", trim(\request()->with));
            foreach ($relations as $relation) {
                if (in_array($relation, $validRelation)) {
                    $itemUnitQuery = $itemUnitQuery->with($relation);
                }
            }
        }

        if (\request()->filled("search")) {
            $searchTerm = '%' . \request()->search . '%';
            $itemUnitQuery->where(function ($query) use ($searchTerm) {
                $query->where('unit_code', 'LIKE', $searchTerm)
                    ->orWhere('merk', 'LIKE', $searchTerm)
                    ->orWhere('condition', 'LIKE', $searchTerm)
                    ->orWhere('notes', 'LIKE', $searchTerm)
                    ->orWhere('diperoleh_dari', 'LIKE', $searchTerm)
                    ->orWhere('current_location', 'LIKE', $searchTerm);
            });
        }

        foreach (request()->except(['page', 'size', 'sortBy', 'sortDir', 'search', 'with']) as $key => $value) {
            if (in_array($key, $validColumns)) {
                if ($key === "diperoleh_tanggal") {
                    $itemUnitQuery->whereDate($key, $value);
                } else {
                    $itemUnitQuery->where($key, $value);
                }
            }
        }

        $sortBy = in_array(request()->sortBy, $validColumns) ? request()->sortBy : 'created_at';
        $sortDir = strtolower(request()->sortDir) === 'desc' ? 'DESC' : 'ASC';
        $itemUnitQuery->orderBy($sortBy, $sortDir);

        $size = min(max(request()->size ?? 10, 1), 100);
        $itemUnits = $itemUnitQuery->simplePaginate($size);

        foreach ($itemUnits as $key => $value) {
            $itemUnits[$key]->qr_image = $value->qr_image ? url($value->qr_image) : null;
        }

        return Formatter::apiResponse(200, 'Item unit list retrieved', $itemUnits);
    }

    public function indexPublic(Request $request): JsonResponse
{
    $user = auth()->guard('sanctum')->user();
    $itemUnitQuery = ItemUnit::query()
        ->whereIn('status', ['available', 'borrowed'])
        ->with(['item.category', 'warehouse']);

    $validColumns = [
        'unit_code', 'merk', 'condition', 'diperoleh_dari',
        'diperoleh_tanggal', 'status', 'quantity',
        'item_id', 'warehouse_id', 'current_location', 'created_at'
    ];

    if ($request->filled('search')) {
        $searchTerm = '%' . $request->search . '%';
        $itemUnitQuery->where(function ($query) use ($searchTerm) {
            $query->where('unit_code', 'LIKE', $searchTerm)
                ->orWhere('merk', 'LIKE', $searchTerm)
                ->orWhere('condition', 'LIKE', $searchTerm)
                ->orWhere('notes', 'LIKE', $searchTerm)
                ->orWhere('diperoleh_dari', 'LIKE', $searchTerm)
                ->orWhere('current_location', 'LIKE', $searchTerm);
        });
    }

    foreach ($request->except(['page', 'size', 'sortBy', 'sortDir', 'search', 'with']) as $key => $value) {
        if (in_array($key, $validColumns)) {
            if ($key === 'diperoleh_tanggal') {
                $itemUnitQuery->whereDate($key, $value);
            } else {
                $itemUnitQuery->where($key, $value);
            }
        }
    }

    $sortBy = in_array($request->sortBy, $validColumns) ? $request->sortBy : 'created_at';
    $sortDir = strtolower($request->sortDir) === 'desc' ? 'DESC' : 'ASC';
    $itemUnitQuery->orderBy($sortBy, $sortDir);

    $size = min(max($request->size ?? 10, 1), 100);
    $itemUnits = $itemUnitQuery->simplePaginate($size);

    foreach ($itemUnits->items() as $key => $unit) {
        $unit->qr_image = $unit->qr_image ? asset('storage/' . $unit->qr_image) : null;
    }

    return Formatter::apiResponse(200, 'Public item unit list retrieved', $itemUnits);
}

public function showPublic($unit_code): JsonResponse
{
    $itemUnit = ItemUnit::with(['item.category', 'warehouse'])->where('unit_code', $unit_code)->first();

    if (!$itemUnit) {
        return Formatter::apiResponse(404, 'Item unit not found');
    }

    $itemUnit->qr_image = $itemUnit->qr_image ? asset('storage/' . $itemUnit->qr_image) : null;
    return Formatter::apiResponse(200, 'Item unit found', $itemUnit);
}

public function store(Request $request)
{
    $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
        'merk' => 'required|string',
        'condition' => 'required|in:Good,Broken,Needs Improvement',
        'notes' => 'nullable|string',
        'diperoleh_dari' => 'required|string',
        'diperoleh_tanggal' => 'required|date',
        'quantity' => 'required|integer|min:1',
        'item_id' => 'required|integer|exists:items,id',
        'warehouse_id' => 'required|integer|exists:warehouses,id',
        'current_location' => 'nullable|string',
    ]);

    if ($validator->fails()) {
        Log::error('Validation failed in ItemUnitController::store', $validator->errors()->toArray());
        return $request->expectsJson()
            ? Formatter::apiResponse(422, 'Validation failed', null, $validator->errors()->all())
            : redirect()->back()->withErrors($validator)->withInput();
    }

    $validated = $validator->validated();

    DB::beginTransaction();
    try {
        Log::info('Starting item unit creation', $validated);

        $warehouse = Warehouse::find($request->warehouse_id);
        $item = Item::find($request->item_id);

        if (!$warehouse || !$item) {
            throw new \Exception('Invalid warehouse or item ID');
        }

        if ($warehouse->capacity < $validated['quantity']) {
            throw new \Exception('Insufficient warehouse capacity');
        }

        if ($item->type === 'non-consumable') {
            $validated['quantity'] = 1;
        }

        $prefix = strtoupper(substr(preg_replace('/\s+/', '', $item->name), 0, 20));
        $lastUnit = ItemUnit::where('unit_code', 'LIKE', $prefix . '%')
            ->orderBy('unit_code', 'desc')
            ->first();

        $newNumber = $lastUnit ? (int)substr($lastUnit->unit_code, -3) + 1 : 1;
        $numberFormatted = str_pad($newNumber, 3, '0', STR_PAD_LEFT);
        $validated['unit_code'] = $prefix . '-' . $numberFormatted;

        // Ensure qr_codes directory exists
        $qrCodeDir = storage_path('app/public/qr_codes');
        if (!file_exists($qrCodeDir)) {
            mkdir($qrCodeDir, 0755, true);
        }

        // Generate QR code in SVG format
        try {
        $path = 'qr_codes/' . $validated['unit_code'] . '.svg';
        $fullPath = storage_path('app/public/' . $path);
        $qrCode = QrCode::format('svg')->size(300)->generate($validated['unit_code']);
        file_put_contents($fullPath, $qrCode);
        $validated['qr_image'] = $path;
        } catch (\Exception $e) {
            Log::warning('QR code generation failed', ['error' => $e->getMessage()]);
            $validated['qr_image'] = null;
        }

        Log::info('Creating item unit', $validated);
        $itemUnit = ItemUnit::create($validated);
        Log::info('Item unit created', ['id' => $itemUnit->id]);

        $warehouse->capacity -= $validated['quantity'];
        $warehouse->save();

        DB::commit();
        return $request->expectsJson()
            ? Formatter::apiResponse(200, 'Item unit created successfully', $itemUnit)
            : redirect()->route('item-units.viewIndex')->with('success', 'Item unit created successfully!');
    } catch (\Exception $e) {
        Log::error('Item unit creation failed', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ]);
        DB::rollBack();
        return $request->expectsJson()
            ? Formatter::apiResponse(500, 'Failed to create item unit: ' . $e->getMessage())
            : redirect()->back()->with('error', 'Failed to create item unit: ' . $e->getMessage())->withInput();
    }
}

public function returnItem(Request $request, $id)
{
    $borrowRequest = BorrowRequest::findOrFail($id);

    if ($borrowRequest->status !== 'approved') {
        return response()->json(['message' => 'Cannot return an item that is not approved.'], 400);
    }

    DB::beginTransaction();
    try {
        $borrowRequest->update(['status' => 'returned']);
        foreach ($borrowRequest->borrowDetails as $detail) {
            $itemUnit = $detail->itemUnit;
            if ($itemUnit->item->type === 'non-consumable') {
                $itemUnit->update(['status' => 'available']);
            }
            // Add stock movement if needed
        }
        DB::commit();
        return response()->json(['message' => 'Item successfully returned.'], 200);
    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json(['message' => 'Failed to return item: ' . $e->getMessage()], 500);
    }
}

public function update(Request $request, string $unit_code)
{
    $itemUnit = ItemUnit::where('unit_code', $unit_code)->first();
    if (is_null($itemUnit)) {
        return $request->expectsJson()
            ? Formatter::apiResponse(404, 'Item unit not found')
            : redirect()->back()->with('error', 'Item unit not found');
    }

    $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
        'unit_code' => 'sometimes|string|unique:item_units,unit_code,' . $itemUnit->id,
        'merk' => 'sometimes|string',
        'condition' => 'sometimes|in:Good,Broken,Needs Improvement',
        'notes' => 'sometimes|nullable|string',
        'diperoleh_dari' => 'sometimes|string',
        'diperoleh_tanggal' => 'sometimes|date',
        'status' => 'sometimes|in:available,borrowed,out_of_stock,unknown,unavailable',        'quantity' => 'sometimes|integer|min:1',
        'item_id' => 'sometimes|exists:items,id',
        'warehouse_id' => 'sometimes|exists:warehouses,id',
        'current_location' => 'sometimes|nullable',
    ]);

    if ($validator->fails()) {
        Log::error('Validation failed in ItemUnitController::update', $validator->errors()->toArray());
        return $request->expectsJson()
            ? Formatter::apiResponse(422, 'Validation failed', null, $validator->errors()->all())
            : redirect()->back()->withErrors($validator)->withInput();
    }

    $validated = $validator->validated();

    DB::beginTransaction();
    try {
        if (isset($validated['warehouse_id']) && isset($validated['quantity'])) {
            $warehouse = Warehouse::find($validated['warehouse_id']);
            $oldQuantity = $itemUnit->quantity;
            $quantityDiff = $validated['quantity'] - $oldQuantity;
            if ($warehouse->capacity < $quantityDiff) {
                throw new \Exception('Insufficient warehouse capacity for updated quantity');
            }
            $warehouse->capacity -= $quantityDiff;
            $warehouse->save();
        }

        if (isset($validated['unit_code']) && $validated['unit_code'] !== $itemUnit->unit_code) {
            $qrCodeDir = storage_path('app/public/qr_codes');
            if (!file_exists($qrCodeDir)) {
                mkdir($qrCodeDir, 0755, true);
            }

            try {
            $path = 'qr_codes/' . $validated['unit_code'] . '.svg';
            $fullPath = storage_path('app/public/' . $path);
            $qrCode = QrCode::format('svg')->size(300)->generate($validated['unit_code']);
            file_put_contents($fullPath, $qrCode);
            chmod($fullPath, 0644);
            // Debug: Cek isi file
            $fileContent = file_get_contents($fullPath);
            Log::info('Generated SVG content', ['path' => $fullPath, 'content_length' => strlen($fileContent)]);
            $validated['qr_image'] = $path;
        } catch (\Exception $e) {
            Log::warning('QR code generation failed', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            $validated['qr_image'] = null;
        }
        }

        $itemUnit->update($validated);
        DB::commit();
        return $request->expectsJson()
            ? Formatter::apiResponse(200, 'Item unit updated successfully', $itemUnit)
            : redirect()->route('item-units.viewIndex')->with('success', 'Item unit updated successfully!');
    } catch (\Exception $e) {
        Log::error('Item unit update failed', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ]);
        DB::rollBack();
        return $request->expectsJson()
            ? Formatter::apiResponse(500, 'Failed to update item unit: ' . $e->getMessage())
            : redirect()->back()->with('error', 'Failed to update item unit: ' . $e->getMessage())->withInput();
    }
}

    public function destroy(string $unit_code)
    {
        Log::info('Attempting to delete item unit', ['unit_code' => $unit_code]);

        $itemUnit = ItemUnit::where('unit_code', $unit_code)->first();
        if (is_null($itemUnit)) {
            Log::warning('Item unit not found for deletion', ['unit_code' => $unit_code]);
            return request()->expectsJson()
                ? Formatter::apiResponse(404, 'Item unit not found')
                : redirect()->back()->with('error', 'Item unit not found');
        }

        DB::beginTransaction();
        try {
            $warehouse = Warehouse::find($itemUnit->warehouse_id);
            $warehouse->capacity += $itemUnit->quantity;
            $warehouse->save();

            $oldPath = str_replace('/storage/', '', $itemUnit->qr_image);
            if ($itemUnit->qr_image && Storage::disk('public')->exists($oldPath)) {
                Storage::disk('public')->delete($oldPath);
            }

            $itemUnit->delete();
            DB::commit();

            Log::info('Item unit deleted successfully', ['unit_code' => $unit_code]);
            return request()->expectsJson()
                ? Formatter::apiResponse(200, 'Item unit deleted successfully')
                : redirect()->route('item-units.viewIndex', ['t' => time()])->with('success', 'Item unit deleted successfully!');
        } catch (\Exception $e) {
            Log::error('Item unit deletion failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            DB::rollBack();
            return request()->expectsJson()
                ? Formatter::apiResponse(500, 'Failed to delete item unit: ' . $e->getMessage())
                : redirect()->back()->with('error', 'Failed to delete item unit: ' . $e->getMessage());
        }
    }

    public function importItemUnits(Request $request)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'file' => 'required|file|mimes:xlsx,csv',
        ]);

        if ($validator->fails()) {
            return $request->expectsJson()
                ? Formatter::apiResponse(422, 'Validation failed', null, $validator->errors()->all())
                : redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();
            Excel::import(new ItemUnitImport, $request->file('file'));
            DB::commit();
            return $request->expectsJson()
                ? Formatter::apiResponse(200, 'Item units imported successfully')
                : redirect()->route('item-units.viewIndex')->with('success', 'Item units imported successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Import failed', ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return $request->expectsJson()
                ? Formatter::apiResponse(500, 'Import failed: ' . $e->getMessage())
                : redirect()->back()->with('error', 'Import failed: ' . $e->getMessage());
        }
    }

    public function exportExcel(Request $request)
    {
        try {
            Log::info('Exporting item units to Excel');
            return Excel::download(new ItemUnitsExport, 'item_units_' . date('Y-m-d_H-i-s') . '.xlsx');
        } catch (\Exception $e) {
            Log::error('Excel export failed', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Failed to export Excel: ' . $e->getMessage());
        }
    }

    public function exportPdf(Request $request)
    {
        try {
            Log::info('Exporting item units to PDF');
            $itemUnits = ItemUnit::with(['item', 'warehouse'])->get();
            $pdf = Pdf::loadView('item-units.pdf', compact('itemUnits'));
            return $pdf->download('item_units_' . date('Y-m-d_H-i-s') . '.pdf');
        } catch (\Exception $e) {
            Log::error('PDF export failed', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Failed to export PDF: ' . $e->getMessage());
        }
    }

    public function viewIndex(Request $request)
    {
        $itemUnitQuery = ItemUnit::query()->with(['item', 'warehouse']);

        if ($request->filled('search')) {
            $searchTerm = '%' . $request->search . '%';
            $itemUnitQuery->where(function ($query) use ($searchTerm) {
                $query->where('unit_code', 'LIKE', $searchTerm)
                    ->orWhere('merk', 'LIKE', $searchTerm)
                    ->orWhere('condition', 'LIKE', $searchTerm)
                    ->orWhere('notes', 'LIKE', $searchTerm)
                    ->orWhere('diperoleh_dari', 'LIKE', $searchTerm)
                    ->orWhere('current_location', 'LIKE', $searchTerm);
            });
        }

        $itemUnits = $itemUnitQuery->paginate(10);
        $response = response()->view('item-units.index', compact('itemUnits'));
        return $response->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
    }

    public function create()
    {
        $items = Item::all();
        $warehouses = Warehouse::all();
        return view('item-units.create', compact('items', 'warehouses'));
    }

    public function edit($unit_code)
    {
        $itemUnit = ItemUnit::with(['item', 'warehouse'])->where('unit_code', $unit_code)->firstOrFail();
        $items = Item::all();
        $warehouses = Warehouse::all();
        return view('item-units.edit', compact('itemUnit', 'items', 'warehouses'));
    }

    public function showView($unit_code)
    {
        $itemUnit = ItemUnit::with(['item.category', 'warehouse'])->where('unit_code', $unit_code)->firstOrFail();
        return view('item-units.show', compact('itemUnit'));
    }

public function downloadQr(string $unit_code)
{
    Log::info('Attempting to download QR code', ['unit_code' => $unit_code]);

    $itemUnit = ItemUnit::where('unit_code', $unit_code)->first();
    if (is_null($itemUnit)) {
        Log::warning('Item unit not found for QR download', ['unit_code' => $unit_code]);
        return redirect()->back()->with('error', 'Item unit not found');
    }

    $relativePath = $itemUnit->qr_image;
    if (is_null($relativePath) || !Storage::disk('public')->exists($relativePath)) {
        Log::warning('QR code image not found for item unit', ['unit_code' => $unit_code, 'path' => $relativePath]);
        return redirect()->back()->with('error', 'QR code image not found');
    }

    $filePath = storage_path('app/public/' . $relativePath);
    $fileExtension = pathinfo($relativePath, PATHINFO_EXTENSION);
    $fileName = 'qr_' . $unit_code . '.' . $fileExtension;

    Log::info('Downloading QR from path', ['path' => $filePath, 'extension' => $fileExtension]);

    return Response::download($filePath, $fileName, [
        'Content-Type' => 'image/svg+xml',
    ]);
}
}

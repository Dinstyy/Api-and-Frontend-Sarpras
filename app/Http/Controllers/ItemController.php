<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Item;
use App\Models\Category;
use App\Custom\Formatter;
use App\Imports\ItemImport;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Exports\ItemsExport;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\PDF;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ItemController extends Controller
{
public function index(): JsonResponse
{
    $itemQuery = Item::query();
    $validColumns = ['name', 'type', 'image', 'qr_image', 'category_id'];
    $validRelation = ["category"];

    // Check if user is authenticated and has role
    $user = auth()->guard('sanctum')->user();
    if ($user && !in_array($user->role, ['admin', 'kepsek'])) {
    }

    if (\request()->filled("with")) {
        $relations = explode(",", trim(\request()->with));
        foreach ($relations as $relation) {
            if (in_array($relation, $validRelation)) {
                $itemQuery = $itemQuery->with($relation);
            }
        }
    }

    if (\request()->filled("search")) {
        $searchTerm = '%' . \request()->search . '%';
        $itemQuery->where(function ($query) use ($searchTerm) {
            $query->where('name', 'LIKE', $searchTerm)
                ->orWhere('description', 'LIKE', $searchTerm);
        });
    }

    foreach (request()->except(['page', 'size', 'sortBy', 'sortDir', 'search', 'with']) as $key => $value) {
        if (in_array($key, $validColumns)) {
            $itemQuery->where($key, $value);
        }
    }

    $sortBy = in_array(request()->sortBy, $validColumns) ? request()->sortBy : 'created_at';
    $sortDir = strtolower(request()->sortDir) === 'desc' ? 'DESC' : 'ASC';
    $itemQuery->orderBy($sortBy, $sortDir);

    $size = min(max(request()->size ?? 10, 1), 100);
    $items = $itemQuery->simplePaginate($size);

    foreach ($items->items() as $key => $value) {
        $items[$key]->image = url($items[$key]->image);
    }

    return Formatter::apiResponse(200, 'Item list retrieved', $items);
}

public function indexPublic(): JsonResponse
{
    $user = auth()->guard('sanctum')->user();
    $itemQuery = Item::query();
    $itemQuery->with(['category']);

    $validColumns = ['name', 'type', 'image', 'qr_image', 'category_id'];
    $validRelation = ['category'];

    if (request()->filled('search')) {
        $searchTerm = '%' . request()->search . '%';
        $itemQuery->where(function ($query) use ($searchTerm) {
            $query->where('name', 'LIKE', $searchTerm)
                  ->orWhere('description', 'LIKE', $searchTerm);
        });
    }

    foreach (request()->except(['page', 'size', 'sortBy', 'sortDir', 'search', 'with']) as $key => $value) {
        if (in_array($key, $validColumns)) {
            $itemQuery->where($key, $value);
        }
    }

    $sortBy = in_array(request()->sortBy, $validColumns) ? request()->sortBy : 'created_at';
    $sortDir = strtolower(request()->sortDir) === 'desc' ? 'DESC' : 'ASC';
    $itemQuery->orderBy($sortBy, $sortDir);

    $size = min(max(request()->size ?? 10, 1), 100);
    $items = $itemQuery->simplePaginate($size);

    foreach ($items->items() as $key => $value) {
        $items[$key]->image = url($items[$key]->image);
    }

    return Formatter::apiResponse(200, 'Public item list retrieved', $items);
}

public function showPublic(int $id)
{
    $user = auth()->guard('sanctum')->user();
    $query = Item::query()->with(["category", "itemUnits.item", "itemUnits.warehouse"]);

    // Kalau bukan kepsek, hanya boleh lihat item yang public
    if (!$user || !in_array($user->role, ['admin', 'kepsek'])) {
    $query = Item::query()->with(["category", "itemUnits.item", "itemUnits.warehouse"]);
    }

    $item = $query->find($id);

    if (!$item) {
        return Formatter::apiResponse(404, "Item not found");
    }

    $item->image = url($item->image);
    foreach ($item->itemUnits as $unit) {
        $unit->qr_image = $unit->qr_image ? url($unit->qr_image) : null;
    }

    return Formatter::apiResponse(200, "Item found", $item);
}

    public function show(int $id)
    {
        $item = Item::query()->with(["category", "itemUnits.item", "itemUnits.warehouse"])->find($id);

        if (is_null($item)) {
            return Formatter::apiResponse(404, "Item not found");
        }

        $item->image = url($item->image);

        foreach ($item->itemUnits as $unit) {
            $unit->qr_image = url($unit->qr_image);
        }

        return Formatter::apiResponse(200, "Item found", $item);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "name" => "required|string|min:3|unique:items,name",
            "type" => "required|string|in:consumable,non-consumable",
            "description" => "sometimes|string",
            "image" => "sometimes|image",
            "category_name" => "required|exists:categories,name"
        ]);

        if ($validator->fails()) {
            return $request->expectsJson()
                ? Formatter::apiResponse(422, 'Validation failed', null, $validator->errors()->all())
                : redirect()->back()->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();
        $validated["category_id"] = Category::where("name", $validated["category_name"])->value("id");

        if ($request->hasFile("image")) {
            $imageFile = $request->file("image");
            $path = "item-images";
            $fileName = Formatter::makeDash($validated["name"] . " upload " . Carbon::now()->toDateString()) . "." . $imageFile->getClientOriginalExtension();
            $storedPath = $imageFile->storeAs($path, $fileName, "public");
            if (!$storedPath) {
                return $request->expectsJson()
                    ? Formatter::apiResponse(500, 'Cannot upload image')
                    : redirect()->back()->with('error', 'Cannot upload image, please try again later')->withInput();
            }
            $validated["image"] = Storage::url($storedPath);
        }

        $newItem = Item::create($validated);
        return $request->expectsJson()
            ? Formatter::apiResponse(200, 'Item created successfully', $newItem)
            : redirect()->route('items.viewIndex')->with('success', 'Item created successfully!');
    }

    public function update(Request $request, int $id)
    {
        $item = Item::find($id);
        if (is_null($item)) {
            return $request->expectsJson()
                ? Formatter::apiResponse(404, 'Item not found')
                : redirect()->back()->with('error', 'Item not found');
        }

        $validator = Validator::make($request->all(), [
            "name" => "sometimes|string|min:3|unique:items,name," . $id,
            "type" => "sometimes|string|in:consumable,non-consumable",
            "description" => "sometimes|string",
            "category_slug" => "sometimes|exists:categories,slug",
            "image" => "sometimes|image"
        ]);

        if ($validator->fails()) {
            return $request->expectsJson()
                ? Formatter::apiResponse(422, 'Validation failed', null, $validator->errors()->all())
                : redirect()->back()->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();
        if ($request->hasFile("image")) {
            $imageFile = $request->file("image");
            $path = "item-images";
            $fileName = Formatter::makeDash($item->name . " upload " . Carbon::now()->toDateString()) . "." . $imageFile->getClientOriginalExtension();
            $storedPath = $imageFile->storeAs($path, $fileName, "public");
            if (!$storedPath) {
                return $request->expectsJson()
                    ? Formatter::apiResponse(500, 'Cannot upload image')
                    : redirect()->back()->with('error', 'Cannot upload image, please try again later')->withInput();
            }
            $validated["image"] = Storage::url($storedPath);
        }

        if ($request->has("category_slug")) {
            $validated["category_id"] = Category::where("slug", $validated["category_slug"])->value("id");
        }

        $item->update($validated);
        return $request->expectsJson()
            ? Formatter::apiResponse(200, 'Item updated successfully', $item)
            : redirect()->route('items.viewIndex')->with('success', 'Item updated successfully!');
    }

    public function destroy(int $id)
    {
        $item = Item::find($id);
        if (is_null($item)) {
            return request()->expectsJson()
                ? Formatter::apiResponse(404, 'Item not found')
                : redirect()->back()->with('error', 'Item not found');
        }

        $item->delete();
        return request()->expectsJson()
            ? Formatter::apiResponse(200, 'Item deleted successfully')
            : redirect()->route('items.viewIndex')->with('success', 'Item deleted successfully!');
    }

    public function updateImage(int $id)
    {
        $item = Item::query()->find($id);
        if (is_null($item)) {
            return redirect()->back()->with('error', 'Item not found');
        }

        $validator = Validator::make(\request()->all(), [
            "image" => "required|image"
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();

        if (\request()->hasFile("image")) {
            $imageFile = \request()->file("image");
            $path = "item-images";
            $fileName = Formatter::makeDash($item->name . " upload " . Carbon::now()->toDateString()) . "." . $imageFile->getClientOriginalExtension();
            $storedPath = $imageFile->storeAs($path, $fileName, "public");
            if (!$storedPath) {
                return redirect()->back()->with('error', 'Cannot upload image, please try again later')->withInput();
            }
            $validated["image"] = Storage::url($storedPath);
        }

        $item->update($validated);
        return redirect()->route('items.viewIndex')->with('success', 'Image updated successfully!');
    }

    public function viewIndex(Request $request)
    {
        $itemQuery = Item::query()->with('category');

        if ($request->filled('search')) {
            $searchTerm = '%' . $request->search . '%';
            $itemQuery->where(function ($query) use ($searchTerm) {
                $query->where('name', 'LIKE', $searchTerm)
                    ->orWhere('description', 'LIKE', $searchTerm);
            });
        }

        $items = $itemQuery->paginate(10);
        return view('items.index', compact('items'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('items.create', compact('categories'));
    }

    public function edit($id)
    {
        $item = Item::with('category')->findOrFail($id);
        $categories = Category::all();
        return view('items.edit', compact('item', 'categories'));
    }

public function showView($id)
{
    $item = Item::with(['category', 'itemUnits', 'itemUnits.warehouse'])->findOrFail($id);
    $item->image = $item->image ? url($item->image) : null;

    foreach ($item->itemUnits as $unit) {
        // Remove any directory prefixes that might be stored in database
        $unit->qr_image = $unit->qr_image ? 'qr_codes/'.basename($unit->qr_image) : null;

        // Check if file exists in storage
        $unit->qr_image_exists = $unit->qr_image ? Storage::disk('public')->exists($unit->qr_image) : false;

        // Set the URL
        $unit->qr_image_url = $unit->qr_image_exists ? asset('storage/'.$unit->qr_image) : null;
    }

    return view('items.show', compact('item'));
}

    public function importItems(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:xlsx,csv',
        ]);

        if ($validator->fails()) {
            return $request->expectsJson()
                ? Formatter::apiResponse(422, 'Validation failed', null, $validator->errors()->all())
                : redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();
            Excel::import(new ItemImport, $request->file('file'));
            DB::commit();
            return $request->expectsJson()
                ? Formatter::apiResponse(200, 'Items imported successfully')
                : redirect()->route('items.viewIndex')->with('success', 'Items imported successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            $errorMessage = 'Import failed: ' . $e->getMessage();
            return $request->expectsJson()
                ? Formatter::apiResponse(500, $errorMessage)
                : redirect()->back()->with('error', $errorMessage);
        }
    }

    public function exportExcel()
    {
        return Excel::download(new ItemsExport, 'items_' . now()->format('Y-m-d_H-i-s') . '.xlsx');
    }

    public function exportPdf()
    {
        $items = Item::with('category')->get();
        $pdf = PDF::loadView('items.pdf', compact('items'));
        return $pdf->download('items_' . now()->format('Y-m-d_H-i-s') . '.pdf');
    }
}

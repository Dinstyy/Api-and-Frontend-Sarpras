<?php

namespace App\Http\Controllers;

use App\Custom\Formatter;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource for web.
     */
    public function viewIndex(Request $request)
    {
        $categories = Category::query()->orderBy('created_at', 'desc')->paginate(10);
        return view('categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('categories.create');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        return view('categories.edit', compact('category'));
    }

    /**
     * Display the specified resource for web.
     */
    public function show(string $slug)
    {
        $category = Category::where('slug', $slug)->with('items')->firstOrFail();
        return view('categories.show', compact('category'));
    }
    /**
     * Display a listing of the resource for API.
     */
    public function index(): JsonResponse
    {
        $categoryQuery = Category::query();

        // Removed 'items' from valid relations to prevent errors
        $validRelation = [];

        if (\request()->filled("columns")) {
            $columns = explode(',', \request()->columns);
            $categoryQuery->select($columns);
        }

        if (\request()->filled("with")) {
            $relations = explode(",", trim(\request()->with));
            foreach ($relations as $relation) {
                if (in_array($relation, $validRelation)) {
                    $categoryQuery = $categoryQuery->with($relation);
                }
            }
        }

        $validColumns = [
            'name', 'description'
        ];

        if (request()->filled('search')) {
            $searchTerm = '%' . request()->search . '%';
            $categoryQuery->where(function ($query) use ($searchTerm) {
                $query->where('name', 'LIKE', $searchTerm);
            });
        }

        foreach (request()->except(['page', 'size', 'sortBy', 'sortDir', 'search', "with"]) as $key => $value) {
            if (in_array($key, $validColumns)) {
                $categoryQuery->where($key, $value);
            }
        }

        $sortBy = in_array(request()->sortBy, $validColumns)
            ? request()->sortBy
            : 'created_at';

        $sortDir = strtolower(request()->sortDir) === 'desc'
            ? 'DESC'
            : 'ASC';

        $categoryQuery->orderBy($sortBy, $sortDir);

        $size = min(max(request()->size ?? 10, 1), 100);

        $categories = $categoryQuery->simplePaginate($size);
        return Formatter::apiResponse(200, "Category list retrieved", $categories);
    }

public function indexPublic(): JsonResponse
{
    $categoryQuery = Category::query(); // Langsung ambil semua kategori

    $validColumns = ['name', 'description'];

    if (request()->filled('search')) {
        $searchTerm = '%' . request()->search . '%';
        $categoryQuery->where(function ($query) use ($searchTerm) {
            $query->where('name', 'LIKE', $searchTerm);
        });
    }

    foreach (request()->except(['page', 'size', 'sortBy', 'sortDir', 'search', 'with']) as $key => $value) {
        if (in_array($key, $validColumns)) {
            $categoryQuery->where($key, $value);
        }
    }

    $sortBy = in_array(request()->sortBy, $validColumns) ? request()->sortBy : 'created_at';
    $sortDir = strtolower(request()->sortDir) === 'desc' ? 'DESC' : 'ASC';
    $categoryQuery->orderBy($sortBy, $sortDir);

    $size = min(max(request()->size ?? 10, 1), 100);
    $categories = $categoryQuery->simplePaginate($size);

    return Formatter::apiResponse(200, 'Public category list retrieved', $categories);
}

public function showPublic($id): JsonResponse
{
    $category = Category::with('items')->find($id);

    if (!$category) {
        return Formatter::apiResponse(404, 'Category not found');
    }

    return Formatter::apiResponse(200, 'Category found', $category);
}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "name" => "required|string|min:5",
            "description" => "nullable|string"
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return Formatter::apiResponse(422, "Validation failed", null, $validator->errors()->all());
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();
        $validated["slug"] = Formatter::makeDash($validated["name"]);

        if (Category::query()->where("slug", $validated["slug"])->exists()) {
            if ($request->expectsJson()) {
                return Formatter::apiResponse(400, "Category already exists");
            }
            return redirect()->back()->withErrors(['name' => 'Category already exists'])->withInput();
        }

        $newCategory = Category::create($validated);

        if ($request->expectsJson()) {
            return Formatter::apiResponse(200, "Category created", $newCategory);
        }
        return redirect()->route('categories.viewIndex')->with('success', 'Category created successfully');
    }

    /**
     * Display the specified resource for API.
     */
public function showApi(string $slug): JsonResponse
{
    try {
        $slug = trim($slug);
        Log::info("Looking for category with slug: $slug");

        $category = Category::query()->where("slug", $slug)->first();
        Log::info("Found category: ", ['category' => $category]);

        return Formatter::apiResponse(200, "Category found", $category ?: ['error' => 'Not found']);
    } catch (\Throwable $e) {
        Log::error("Error in showApi: " . $e->getMessage());
        return Formatter::apiResponse(500, "Server error", null, [$e->getMessage()]);
    }
}
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $slug)
    {
        $category = Category::where("slug", trim($slug))->first();
        if (is_null($category)) {
            if ($request->expectsJson()) {
                return Formatter::apiResponse(404, "Category not found");
            }
            return redirect()->route('categories.viewIndex')->withErrors(['error' => 'Category not found']);
        }

        $validator = Validator::make($request->all(), [
            "name" => "sometimes|string|min:5",
            "description" => "nullable|string"
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return Formatter::apiResponse(422, "Validation failed", null, $validator->errors()->all());
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();
        if (isset($validated["name"])) {
            $validated["slug"] = Formatter::makeDash($validated["name"]);
            if ($slug !== $validated["slug"] && Category::where("slug", $validated["slug"])->exists()) {
                if ($request->expectsJson()) {
                    return Formatter::apiResponse(400, "Category already exists");
                }
                return redirect()->back()->withErrors(['name' => 'Category already exists'])->withInput();
            }
        }

        $category->update($validated);

        if ($request->expectsJson()) {
            return Formatter::apiResponse(200, "Category updated", Category::find($category->id));
        }
        return redirect()->route('categories.viewIndex')->with('success', 'Category updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $slug)
    {
        $category = Category::where("slug", trim($slug))->first();
        if (is_null($category)) {
            if (request()->expectsJson()) {
                return Formatter::apiResponse(404, "Category not found");
            }
            return redirect()->route('categories.viewIndex')->withErrors(['error' => 'Category not found']);
        }

        $category->delete();

        if (request()->expectsJson()) {
            return Formatter::apiResponse(200, "Category deleted");
        }
        return redirect()->route('categories.viewIndex')->with('success', 'Category deleted successfully');
    }
}

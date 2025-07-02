<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix("auth")->group(function () {
    Route::post("login", [\App\Http\Controllers\AuthController::class, "login"]);
    Route::middleware("need-token")->group(function () {
        Route::post("logout", [\App\Http\Controllers\AuthController::class, "logout"]);
        Route::get("self", [\App\Http\Controllers\AuthController::class, "self"]);
    });
});

Route::middleware(["need-token", "role:admin"])->prefix("admin")->group(function () {
    Route::apiResource("users", \App\Http\Controllers\UserController::class);
    Route::post('users/import', [\App\Http\Controllers\UserController::class, 'importUsers'])->name('users.import');
    Route::apiResource("categories", \App\Http\Controllers\CategoryController::class);
    Route::get('categories/slug/{slug}', [\App\Http\Controllers\CategoryController::class, 'showApi']);
    Route::apiResource("warehouses", \App\Http\Controllers\WarehouseController::class);
    Route::apiResource("items", \App\Http\Controllers\ItemController::class);
    Route::post("items/import", [\App\Http\Controllers\ItemController::class, "importItems"])->name("items.import");
    Route::apiResource("items-units", \App\Http\Controllers\ItemUnitController::class)->parameters(['items-units' => 'unit_code']);
    Route::post("items-units/import", [\App\Http\Controllers\ItemUnitController::class, "importItemUnits"])->name("items-units.import");
    Route::apiResource("return-requests", \App\Http\Controllers\ReturnRequestController::class)->except(['edit', 'create']);
    Route::post("return-requests/{returnRequest}/approve", [\App\Http\Controllers\ReturnRequestController::class, 'approve']);
    Route::post("return-requests/{returnRequest}/reject", [\App\Http\Controllers\ReturnRequestController::class, 'reject']);
    Route::apiResource("return-details", \App\Http\Controllers\ReturnDetailController::class)->only(['store', 'destroy']);
});

Route::middleware(["need-token", "role:siswa,guru"])->prefix("user")->group(function () {
    Route::get('items', [\App\Http\Controllers\ItemController::class, 'indexPublic']);
    Route::get("items/{id}", [\App\Http\Controllers\ItemController::class, 'showPublic']);
    Route::get('categories', [\App\Http\Controllers\CategoryController::class, 'indexPublic']);
    Route::get('categories/{id}', [\App\Http\Controllers\CategoryController::class, 'showPublic']);
    Route::get("items-units", [\App\Http\Controllers\ItemUnitController::class, 'indexPublic']);
    Route::get("items-units/{id}", [\App\Http\Controllers\ItemUnitController::class, 'showPublic']);
    Route::get("warehouses", [\App\Http\Controllers\WarehouseController::class, 'indexPublic']);
    Route::get("warehouses/{id}", [\App\Http\Controllers\WarehouseController::class, 'showPublic']);
    Route::apiResource("borrow-requests", \App\Http\Controllers\BorrowRequestController::class);
    Route::post('/borrow-requests', [\App\Http\Controllers\BorrowRequestController::class, 'store'])->name('borrow-requests.store'); // Explicit POST route
    Route::get('borrow-history', [\App\Http\Controllers\BorrowRequestController::class, 'getUserBorrowHistory'])->name('borrow-history.index');
    Route::get('active-borrows', [\App\Http\Controllers\BorrowRequestController::class, 'getActiveBorrows'])->name('active-borrows.index'); // Add this line
    Route::apiResource("return-requests", \App\Http\Controllers\ReturnRequestController::class)->only(['index', 'store', 'show']);
    Route::get('return-history', [\App\Http\Controllers\ReturnRequestController::class, 'getUserReturnHistory'])->name('return-history.index');
    Route::post("borrow-requests/{id}/return", [\App\Http\Controllers\BorrowRequestController::class, 'returnItem'])->name('borrow-requests.return');
});

Route::fallback(function () {
    return \App\Custom\Formatter::apiResponse(404, "Route not found");
});

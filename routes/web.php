<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ItemUnitController;
use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\BorrowDetailController;
use App\Http\Controllers\ReturnDetailController;
use App\Http\Controllers\BorrowRequestController;
use App\Http\Controllers\ReturnRequestController;
use App\Http\Controllers\StockMovementController;

Route::get('/', function () {
    if (auth()->check() && in_array(auth()->user()->role, ['admin', 'kepsek'])) {
        return redirect()->route('dashboard');
    }
    return view('welcome');
})->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Routes accessible to authenticated users (including non-admins)
Route::middleware('auth')->group(function () {
    // Borrow Request Routes for creating
    Route::get('/borrow-requests/create', [BorrowRequestController::class, 'create'])->name('borrow-requests.create');
    Route::post('/borrow-requests', [BorrowRequestController::class, 'store'])->name('borrow-requests.store');
});

// Routes restricted to admin or kepsek
Route::middleware(['auth', 'role:admin,kepsek'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');

    // Borrow Request Routes for admin actions
    Route::get('/borrow-requests', [BorrowRequestController::class, 'index'])->name('borrow-requests.index');
    Route::get('/borrow-requests/{borrowRequest}', [BorrowRequestController::class, 'show'])->name('borrow-requests.show');
    Route::get('/borrow-requests/{borrowRequest}/edit', [BorrowRequestController::class, 'edit'])->name('borrow-requests.edit');
    Route::put('/borrow-requests/{borrowRequest}', [BorrowRequestController::class, 'update'])->name('borrow-requests.update');
    Route::delete('/borrow-requests/{borrowRequest}', [BorrowRequestController::class, 'destroy'])->name('borrow-requests.destroy');
    Route::patch('/borrow-requests/{borrowRequest}/approve', [BorrowRequestController::class, 'approve'])->name('borrow-requests.approve');
    Route::patch('/borrow-requests/{borrowRequest}/reject', [BorrowRequestController::class, 'reject'])->name('borrow-requests.reject');
    Route::get('/borrow-requests/export/excel', [BorrowRequestController::class, 'exportExcel'])->name('borrow-requests.exportExcel');
    Route::get('/borrow-requests/export/pdf', [BorrowRequestController::class, 'exportPdf'])->name('borrow-requests.exportPdf');
    Route::get('/borrow-requests/active', [BorrowRequestController::class, 'getActiveBorrows'])->name('borrow-requests.active');

    // Return Request Routes
    Route::get('/return-requests', [ReturnRequestController::class, 'index'])->name('return-requests.index');
    Route::get('/return-requests/{returnRequest}', [ReturnRequestController::class, 'show'])->name('return-requests.show');
    Route::post('/return-requests', [ReturnRequestController::class, 'store'])->name('return-requests.store');
    Route::delete('/return-requests/{returnRequest}', [ReturnRequestController::class, 'destroy'])->name('return-requests.destroy');
    Route::patch('/return-requests/{returnRequest}/approve', [ReturnRequestController::class, 'approve'])->name('return-requests.approve');
    Route::patch('/return-requests/{returnRequest}/reject', [ReturnRequestController::class, 'reject'])->name('return-requests.reject');
    Route::get('/return-requests/export/excel', [ReturnRequestController::class, 'exportExcel'])->name('return-requests.exportExcel');
    Route::get('/return-requests/export/pdf', [ReturnRequestController::class, 'exportPdf'])->name('return-requests.exportPdf');

    // Return Detail Routes
    Route::get('/return-details/{returnRequestId}/create', [ReturnDetailController::class, 'create'])->name('return-details.create');
    Route::post('/return-details', [ReturnDetailController::class, 'store'])->name('return-details.store');
    Route::delete('/return-details/{returnDetail}', [ReturnDetailController::class, 'destroy'])->name('return-details.destroy');

    // Activity Log Routes
    Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');
    Route::get('/activity-logs/export/excel', [ActivityLogController::class, 'exportExcel'])->name('activity-logs.exportExcel');
    Route::get('/activity-logs/export/pdf', [ActivityLogController::class, 'exportPdf'])->name('activity-logs.exportPdf');

    // Borrow Detail Routes
    Route::get('/borrow-details/{borrowRequestId}/create', [BorrowDetailController::class, 'create'])->name('borrow-details.create');
    Route::post('/borrow-details', [BorrowDetailController::class, 'store'])->name('borrow-details.store');
    Route::delete('/borrow-details/{borrowDetails}', [BorrowRequestController::class, 'destroy'])->name('borrow-details.destroy');

    // User Routes
    Route::get('/users', [UserController::class, 'viewIndex'])->name('users.viewIndex');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{id}', [UserController::class, 'show'])->name('users.show');
    Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::get('/users/export/excel', [UserController::class, 'exportExcel'])->name('users.exportExcel');
    Route::get('/users/export/pdf', [UserController::class, 'exportPdf'])->name('users.exportPdf');
    Route::post('/users/import', [UserController::class, 'importUsers'])->name('users.import');

    // Category Routes
    Route::get('/categories', [CategoryController::class, 'viewIndex'])->name('categories.viewIndex');
    Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::get('/categories/{slug}', [CategoryController::class, 'show'])->name('categories.show');
    Route::get('/categories/{slug}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
    Route::put('/categories/{slug}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{slug}', [CategoryController::class, 'destroy'])->name('categories.destroy');

    // Warehouse Routes
    Route::get('/warehouses', [WarehouseController::class, 'viewIndex'])->name('warehouses.viewIndex');
    Route::get('/warehouses/create', [WarehouseController::class, 'create'])->name('warehouses.create');
    Route::post('/warehouses', [WarehouseController::class, 'store'])->name('warehouses.store');
    Route::get('/warehouses/{id}', [WarehouseController::class, 'show'])->name('warehouses.show');
    Route::get('/warehouses/{id}/edit', [WarehouseController::class, 'edit'])->name('warehouses.edit');
    Route::put('/warehouses/{id}', [WarehouseController::class, 'update'])->name('warehouses.update');
    Route::delete('/warehouses/{id}', [WarehouseController::class, 'destroy'])->name('warehouses.destroy');

    // Item Routes
    Route::get('/items', [ItemController::class, 'viewIndex'])->name('items.viewIndex');
    Route::get('/items/create', [ItemController::class, 'create'])->name('items.create');
    Route::post('/items', [ItemController::class, 'store'])->name('items.store');
    Route::get('/items/{id}/show', [ItemController::class, 'showView'])->name('items.showView');
    Route::get('/items/{id}/edit', [ItemController::class, 'edit'])->name('items.edit');
    Route::put('/items/{id}', [ItemController::class, 'update'])->name('items.update');
    Route::delete('/items/{id}', [ItemController::class, 'destroy'])->name('items.destroy');
    Route::post('/items/import', [ItemController::class, 'importItems'])->name('items.import');
    Route::get('/items/export/excel', [ItemController::class, 'exportExcel'])->name('items.exportExcel');
    Route::get('/items/export/pdf', [ItemController::class, 'exportPdf'])->name('items.exportPdf');

    // Item Unit Routes
    Route::get('/item-units', [ItemUnitController::class, 'viewIndex'])->name('item-units.viewIndex');
    Route::get('/item-units/create', [ItemUnitController::class, 'create'])->name('item-units.create');
    Route::post('/item-units', [ItemUnitController::class, 'store'])->name('item-units.store');
    Route::get('/item-units/{unit_code}/show', [ItemUnitController::class, 'showView'])->name('item-units.showView');
    Route::get('/item-units/{unit_code}/edit', [ItemUnitController::class, 'edit'])->name('item-units.edit');
    Route::put('/item-units/{unit_code}', [ItemUnitController::class, 'update'])->name('item-units.update');
    Route::delete('/item-units/{unit_code}', [ItemUnitController::class, 'destroy'])->name('item-units.destroy');
    Route::post('/item-units/import', [ItemUnitController::class, 'importItemUnits'])->name('item-units.import');
    Route::get('/item-units/export/excel', [ItemUnitController::class, 'exportExcel'])->name('item-units.exportExcel');
    Route::get('/item-units/export/pdf', [ItemUnitController::class, 'exportPdf'])->name('item-units.exportPdf');
    Route::get('/item-units/{unit_code}/qr', [ItemUnitController::class, 'downloadQr'])->name('item-units.downloadQr');
    Route::get('/item-units/scan/{unit_code}', [ItemUnitController::class, 'showScanUnitCode'])->name('item-units.scan');

    // Stock Movement Routes
    Route::get('/stock-movements', [StockMovementController::class, 'index'])->name('stock_movements.index');
    Route::post('/stock-movements', [StockMovementController::class, 'store'])->name('stock_movements.store');
});

Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});

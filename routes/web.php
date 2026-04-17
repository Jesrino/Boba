<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CashierPosController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return auth()->user()->isAdmin()
            ? redirect()->route('admin.dashboard')
            : redirect()->route('cashier.pos');
    })->name('dashboard');

    Route::get('/pos', function () {
        return auth()->user()->isAdmin()
            ? redirect()->route('admin.dashboard')
            : redirect()->route('cashier.pos');
    })->name('pos.index');

});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'role:cashier'])->prefix('cashier')->name('cashier.')->group(function () {
    Route::get('/pos', [CashierPosController::class, 'index'])->name('pos');
    Route::post('/checkout', [CashierPosController::class, 'store'])->name('checkout');
    Route::get('/transactions', [CashierPosController::class, 'history'])->name('transactions.index');
    Route::get('/transactions/{transaction}/receipt', [CashierPosController::class, 'showReceipt'])->name('receipt.show');
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/cashiers', [AdminController::class, 'cashiers'])->name('cashiers.index');
    Route::get('/products', [AdminController::class, 'products'])->name('products.index');
    Route::post('/products', [AdminController::class, 'storeProduct'])->name('products.store');
    Route::put('/products/{product}', [AdminController::class, 'updateProduct'])->name('products.update');
    Route::get('/inventory', [AdminController::class, 'inventory'])->name('inventory.index');
    Route::post('/inventory', [AdminController::class, 'storeInventoryItem'])->name('inventory.store');
    Route::put('/inventory/{inventoryItem}', [AdminController::class, 'updateInventoryItem'])->name('inventory.update');
    Route::get('/sales', [AdminController::class, 'sales'])->name('sales.index');
});

require __DIR__.'/auth.php';

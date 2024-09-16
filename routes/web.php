<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StockMovementController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ServiceController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\OfferController;

Route::resource('offers', OfferController::class);

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ProductController
Route::resource('products', ProductController::class);

// StockMovementController
Route::get('products/{product}/stock-movement/create', [StockMovementController::class, 'create'])->name('stock.create');
Route::post('products/stock-movement/store', [StockMovementController::class, 'store'])->name('stock.move');
Route::get('products/{product}/stock-movement/history', [StockMovementController::class, 'history'])->name('stock.history');

// Ostrzeżenia o niskich stanach magazynowych
Route::get('products/low-stock', [ProductController::class, 'checkLowStock'])->name('products.low_stock');

// ClientController
Route::resource('clients', ClientController::class);

// ServiceController
Route::resource('services', ServiceController::class);

require __DIR__.'/auth.php';

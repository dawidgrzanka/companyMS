<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StockMovementController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\MeetingController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\ExpenseController;
use Illuminate\Support\Facades\Route;

Route::resource('offers', OfferController::class);
Route::resource('invoices', InvoiceController::class);
Route::resource('meetings', MeetingController::class);
Route::resource('notes', NoteController::class);

Route::get('invoices/{invoice}/exportToPDF', [InvoiceController::class, 'exportToPDF'])->name('invoices.exportToPDF');

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

// TaskController
Route::resource('tasks', TaskController::class);

Route::middleware('auth')->group(function () {
    Route::post('/tasks/{task}/add-material', [TaskController::class, 'addMaterialToTask'])->name('tasks.addMaterial');
    Route::post('/tasks/{task}/materials/{materialUsage}/increase', [TaskController::class, 'increaseMaterialQuantity'])->name('tasks.increaseMaterial');
    Route::post('/tasks/{task}/materials/{materialUsage}/decrease', [TaskController::class, 'decreaseMaterialQuantity'])->name('tasks.decreaseMaterial');
    Route::delete('/tasks/{task}/materials/{materialUsage}/remove', [TaskController::class, 'removeMaterial'])->name('tasks.removeMaterial');
    Route::get('/tasks/{id}/export-pdf', [TaskController::class, 'exportToPdf'])->name('tasks.exportToPdf');

});

//ExpenseController
Route::middleware(['auth'])->group(function () {
    Route::get('/expenses', [ExpenseController::class, 'index'])->name('expenses.index'); // Lista wydatków
    Route::get('/expenses/create', [ExpenseController::class, 'create'])->name('expenses.create'); // Formularz dodawania
    Route::post('/expenses', [ExpenseController::class, 'store'])->name('expenses.store'); // Zapisywanie wydatku
    Route::get('/expenses/{expense}', [ExpenseController::class, 'show'])->name('expenses.show'); // Podgląd wydatku
    Route::get('/expenses/{expense}/edit', [ExpenseController::class, 'edit'])->name('expenses.edit'); // Edycja
    Route::put('/expenses/{expense}', [ExpenseController::class, 'update'])->name('expenses.update'); // Aktualizacja
    Route::delete('/expenses/{expense}', [ExpenseController::class, 'destroy'])->name('expenses.destroy'); // Usuwanie
});

require __DIR__.'/auth.php';

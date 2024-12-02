<?php

use App\Models\Threshold;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StockController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\IncomingController;
use App\Http\Controllers\OutgoingController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\ThresholdController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard', [
        'title' => 'Dashboard'
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/product', [ProductController::class, 'index'])->name('product.index');
    Route::get('/product/create', [ProductController::class, 'create'])->name('product.create');
    Route::post('/product', [ProductController::class, 'store'])->name('product.store');
    Route::get('product/{product}/edit', [ProductController::class, 'edit'])->name('product.edit');
    Route::put('product/{product}', [ProductController::class, 'update'])->name('product.update');
    Route::delete('product/{product}', [ProductController::class, 'destroy'])->name('product.destroy');

    Route::get('/incoming', [IncomingController::class, 'index'])->name('incoming.index');
    Route::get('/incoming/create', [IncomingController::class, 'create'])->name('incoming.create');
    Route::post('/incoming', [IncomingController::class, 'store'])->name('incoming.store');
    Route::get('incoming/{incoming}/edit', [IncomingController::class, 'edit'])->name('incoming.edit');
    Route::put('incoming/{incoming}', [IncomingController::class, 'update'])->name('incoming.update');
    Route::delete('incoming/{incoming}', [IncomingController::class, 'destroy'])->name('incoming.destroy');

    Route::get('/outgoing', [OutgoingController::class, 'index'])->name('outgoing.index');
    Route::get('/outgoing/create', [OutgoingController::class, 'create'])->name('outgoing.create');
    Route::post('/outgoing', [OutgoingController::class, 'store'])->name('outgoing.store');
    Route::get('outgoing/{outgoing}/edit', [OutgoingController::class, 'edit'])->name('outgoing.edit');
    Route::put('outgoing/{outgoing}', [OutgoingController::class, 'update'])->name('outgoing.update');
    Route::delete('outgoing/{outgoing}', [OutgoingController::class, 'destroy'])->name('outgoing.destroy');

    Route::get('/stock', [StockController::class, 'index'])->name('stock.index');
    Route::get('/stock/create', [StockController::class, 'create'])->name('stock.create');
    Route::post('/stock', [StockController::class, 'store'])->name('stock.store');
    Route::get('stock/{stock}/edit', [StockController::class, 'edit'])->name('stock.edit');
    Route::put('stock/{stock}', [StockController::class, 'update'])->name('stock.update');
    Route::delete('stock/{stock}', [StockController::class, 'destroy'])->name('stock.destroy');

    Route::get('/threshold', [ThresholdController::class, 'index'])->name('threshold.index');
    Route::get('/threshold/create', [ThresholdController::class, 'create'])->name('threshold.create');
    Route::post('/threshold', [ThresholdController::class, 'store'])->name('threshold.store');
    Route::get('threshold/{threshold}/edit', [ThresholdController::class, 'edit'])->name('threshold.edit');
    Route::put('threshold/{threshold}', [ThresholdController::class, 'update'])->name('threshold.update');
    Route::delete('threshold/{threshold}', [ThresholdController::class, 'destroy'])->name('threshold.destroy');

    Route::get('/supplier', [SupplierController::class, 'index'])->name('supplier.index');
    Route::get('/supplier/create', [SupplierController::class, 'create'])->name('supplier.create');
    Route::post('/supplier', [SupplierController::class, 'store'])->name('supplier.store');
    Route::get('supplier/{supplier}/edit', [SupplierController::class, 'edit'])->name('supplier.edit');
    Route::put('supplier/{supplier}', [SupplierController::class, 'update'])->name('supplier.update');
    Route::delete('supplier/{supplier}', [SupplierController::class, 'destroy'])->name('supplier.destroy');

    Route::get('/customer', [CustomerController::class, 'index'])->name('customer.index');
    Route::get('/customer/create', [CustomerController::class, 'create'])->name('customer.create');
    Route::post('/customer', [CustomerController::class, 'store'])->name('customer.store');
    Route::get('customer/{customer}/edit', [CustomerController::class, 'edit'])->name('customer.edit');
    Route::put('customer/{customer}', [CustomerController::class, 'update'])->name('customer.update');
    Route::delete('customer/{customer}', [CustomerController::class, 'destroy'])->name('customer.destroy');
});

require __DIR__ . '/auth.php';

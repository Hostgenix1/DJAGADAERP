<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('products/data', [ProductController::class, 'datatable'])->name('products.datatable');
    Route::resource('products', ProductController::class);
});

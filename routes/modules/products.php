<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('products', ProductController::class)->except('show');
    Route::get('products/data', [ProductController::class, 'datatable'])->name('products.datatable');
});

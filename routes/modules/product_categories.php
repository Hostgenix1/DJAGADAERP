<?php

use App\Http\Controllers\ProductCategoryController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('product_categories', ProductCategoryController::class)->except('show');
    Route::get('product_categories/data', [ProductCategoryController::class, 'datatable'])->name('product_categories.datatable');
});

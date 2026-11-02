<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('products/data', [ProductController::class, 'datatable'])->name('products.datatable');
    Route::post('products/{product}/images', [ProductController::class, 'storeImages'])->name('products.images.store');
    Route::post('products/{product}/images/{image}/primary', [ProductController::class, 'primaryImage'])->name('products.images.primary');
    Route::delete('products/{product}/images/{image}', [ProductController::class, 'destroyImage'])->name('products.images.destroy');
    Route::resource('products', ProductController::class);
});

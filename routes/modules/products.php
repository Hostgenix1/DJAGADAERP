<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('products/data', [ProductController::class, 'datatable'])->name('products.datatable');
    Route::get('products/sku/next', [ProductController::class, 'nextSku'])->name('products.sku.next');
    Route::post('products/{product}/toggle-active', [ProductController::class, 'toggleActive'])->name('products.toggle-active');
    Route::post('products/{product}/images', [ProductController::class, 'storeImages'])->name('products.images.store');
    Route::post('products/{product}/images/{image}/primary', [ProductController::class, 'primaryImage'])->name('products.images.primary');
    Route::delete('products/{product}/images/{image}', [ProductController::class, 'destroyImage'])->name('products.images.destroy');
    Route::resource('products', ProductController::class);
});

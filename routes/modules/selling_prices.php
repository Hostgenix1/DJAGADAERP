<?php

use App\Http\Controllers\SellingPriceController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('selling-prices/data', [SellingPriceController::class, 'datatable'])->name('selling_prices.datatable');
    Route::post('selling-prices/{sellingPrice}/approve', [SellingPriceController::class, 'approve'])->name('selling_prices.approve');
    Route::resource('selling-prices', SellingPriceController::class)->except(['show'])->names('selling_prices');
});

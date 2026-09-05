<?php

use App\Http\Controllers\SupplierPriceController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('supplier-prices/data', [SupplierPriceController::class, 'datatable'])->name('supplier_prices.datatable');
    Route::resource('supplier-prices', SupplierPriceController::class)->except(['show'])->names('supplier_prices');
});

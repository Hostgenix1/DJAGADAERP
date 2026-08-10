<?php

use App\Http\Controllers\SupplierController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('suppliers', SupplierController::class)->except(['show']);
    Route::get('suppliers/data', [SupplierController::class, 'datatable'])->name('suppliers.datatable');
    Route::get('suppliers/{supplier}', [SupplierController::class, 'show'])->name('suppliers.show');
});

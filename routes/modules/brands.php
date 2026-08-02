<?php

use App\Http\Controllers\BrandController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('brands', BrandController::class)->except('show');
    Route::get('brands/data', [BrandController::class, 'datatable'])->name('brands.datatable');
});

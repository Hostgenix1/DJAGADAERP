<?php

use App\Http\Controllers\CurrencyController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('currencies', CurrencyController::class)->except('show');
    Route::get('currencies/data', [CurrencyController::class, 'datatable'])->name('currencies.datatable');
});

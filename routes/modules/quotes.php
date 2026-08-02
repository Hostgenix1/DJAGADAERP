<?php

use App\Http\Controllers\QuoteController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('quotes/data', [QuoteController::class, 'datatable'])->name('quotes.datatable');
    Route::get('quotes/{quote}/convert/{type}', [QuoteController::class, 'convertToInvoice'])->name('quotes.convert');
    Route::resource('quotes', QuoteController::class);
});

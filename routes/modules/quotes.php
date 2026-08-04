<?php

use App\Http\Controllers\QuoteController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('quotes/data', [QuoteController::class, 'datatable'])->name('quotes.datatable');
    Route::post('quotes/{quote}/convert/{type}', [QuoteController::class, 'convertToInvoice'])->name('quotes.convert');
    Route::get('quotes/{quote}/pdf', [QuoteController::class, 'pdf'])->name('quotes.pdf');
    Route::resource('quotes', QuoteController::class);
});

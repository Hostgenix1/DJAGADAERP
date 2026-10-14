<?php

use App\Http\Controllers\InvoiceController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('invoices/data', [InvoiceController::class, 'datatable'])->name('invoices.datatable');
    Route::get('invoices/{invoice}/pdf', [InvoiceController::class, 'pdf'])->name('invoices.pdf');
    Route::get('proforma-invoices', fn () => redirect()->route('invoices.index', ['type' => 'proforma']))
        ->name('proforma_invoices.index');
    Route::resource('invoices', InvoiceController::class);
});

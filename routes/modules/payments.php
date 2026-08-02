<?php

use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('payments/data', [PaymentController::class, 'datatable'])->name('payments.datatable');
    Route::get('payments/outstanding', [PaymentController::class, 'outstanding'])->name('payments.outstanding');
    Route::get('payments/outstanding-json', [PaymentController::class, 'outstandingJson'])->name('payments.outstanding-json');
    Route::resource('payments', PaymentController::class)->except(['edit', 'update']);
});

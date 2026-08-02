<?php

use App\Http\Controllers\CompanyBankAccountController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('bank-accounts/data', [CompanyBankAccountController::class, 'datatable'])->name('bank-accounts.datatable');
    Route::resource('bank-accounts', CompanyBankAccountController::class)->except(['show']);
});

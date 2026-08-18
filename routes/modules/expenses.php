<?php

use App\Http\Controllers\ExpenseController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('expenses/data', [ExpenseController::class, 'datatable'])->name('expenses.datatable');
    Route::resource('expenses', ExpenseController::class)->except(['show']);
});
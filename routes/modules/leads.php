<?php

use App\Http\Controllers\LeadController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('leads/data', [LeadController::class, 'datatable'])->name('leads.datatable');
    Route::resource('leads', LeadController::class);
});

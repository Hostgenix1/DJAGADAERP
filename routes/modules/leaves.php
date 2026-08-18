<?php

use App\Http\Controllers\LeaveController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('leaves/data', [LeaveController::class, 'datatable'])->name('leaves.datatable');
    Route::resource('leaves', LeaveController::class)->except(['show']);
});
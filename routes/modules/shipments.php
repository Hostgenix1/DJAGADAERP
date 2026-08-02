<?php

use App\Http\Controllers\ShipmentController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('shipments/data', [ShipmentController::class, 'datatable'])->name('shipments.datatable');
    Route::resource('shipments', ShipmentController::class);
});

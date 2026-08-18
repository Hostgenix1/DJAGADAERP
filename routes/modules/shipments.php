<?php

use App\Http\Controllers\ShipmentController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('shipments/data', [ShipmentController::class, 'datatable'])->name('shipments.datatable');
    Route::patch('shipments/{shipment}/status', [ShipmentController::class, 'updateStatus'])->name('shipments.update-status');
    Route::get('shipments/{shipment}/pdf', [ShipmentController::class, 'pdf'])->name('shipments.pdf');
    Route::resource('shipments', ShipmentController::class);
});

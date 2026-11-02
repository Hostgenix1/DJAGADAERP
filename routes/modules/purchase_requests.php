<?php

use App\Http\Controllers\PurchaseRequestController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('purchase-requests/data', [PurchaseRequestController::class, 'datatable'])->name('purchase_requests.datatable');
    Route::post('purchase-requests/{purchaseRequest}/submit', [PurchaseRequestController::class, 'submit'])->name('purchase_requests.submit');
    Route::post('purchase-requests/{purchaseRequest}/approve', [PurchaseRequestController::class, 'approve'])->name('purchase_requests.approve');
    Route::post('purchase-requests/{purchaseRequest}/reject', [PurchaseRequestController::class, 'reject'])->name('purchase_requests.reject');
    Route::post('purchase-requests/{purchaseRequest}/convert', [PurchaseRequestController::class, 'convert'])->name('purchase_requests.convert');
    Route::resource('purchase-requests', PurchaseRequestController::class)
        ->names('purchase_requests')
        ->parameters(['purchase-requests' => 'purchaseRequest']);
});

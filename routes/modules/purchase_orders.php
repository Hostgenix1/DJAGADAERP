<?php

use App\Http\Controllers\PurchaseOrderController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('purchase-orders/data', [PurchaseOrderController::class, 'datatable'])->name('purchase_orders.datatable');
    Route::get('purchase-orders/{purchaseOrder}/pdf', [PurchaseOrderController::class, 'pdf'])->name('purchase_orders.pdf');
    Route::post('purchase-orders/{purchaseOrder}/status', [PurchaseOrderController::class, 'status'])->name('purchase_orders.status');
    Route::get('purchase-orders/create', [PurchaseOrderController::class, 'create'])->name('purchase_orders.create');
    Route::resource('purchase-orders', PurchaseOrderController::class)->except('create')->parameters(['purchase-orders' => 'purchaseOrder'])->names('purchase_orders');
});

<?php

use App\Http\Controllers\SupplierBillController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('supplier-bills/data', [SupplierBillController::class, 'datatable'])->name('supplier_bills.datatable');
    Route::get('supplier-bills/{supplierBill}/pdf', [SupplierBillController::class, 'pdf'])->name('supplier_bills.pdf');
    Route::post('supplier-bills/convert-from-po/{purchaseOrder}', [SupplierBillController::class, 'convertFromPo'])->name('supplier_bills.convert');
    Route::post('supplier-bills/{supplierBill}/status', [SupplierBillController::class, 'status'])->name('supplier_bills.status');
    Route::get('supplier-bills/create', [SupplierBillController::class, 'create'])->name('supplier_bills.create');
    Route::resource('supplier-bills', SupplierBillController::class)->except('create')->parameters(['supplier-bills' => 'supplierBill'])->names('supplier_bills');
});

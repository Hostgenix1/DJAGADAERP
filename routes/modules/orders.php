<?php

use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('orders/data', [OrderController::class, 'datatable'])->name('orders.datatable');
    Route::resource('orders', OrderController::class);
});

<?php

use App\Http\Controllers\StockController;
use App\Http\Controllers\WarehouseController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('stock/data', [StockController::class, 'datatable'])->name('stock.datatable');
    Route::get('stock/movements', [StockController::class, 'movements'])->name('stock.movements');
    Route::get('stock/movements/data', [StockController::class, 'movementsDatatable'])->name('stock.movements.datatable');
    Route::get('stock/movements/create', [StockController::class, 'createMovement'])->name('stock.movements.create');
    Route::post('stock/movements', [StockController::class, 'storeMovement'])->name('stock.movements.store');
    Route::delete('stock/movements/{stockMovement}', [StockController::class, 'destroyMovement'])->name('stock.movements.destroy');
    Route::get('stock', [StockController::class, 'index'])->name('stock.index');

    Route::get('warehouses/data', [WarehouseController::class, 'datatable'])->name('warehouses.datatable');
    Route::resource('warehouses', WarehouseController::class)->except(['show']);
});

<?php

use App\Http\Controllers\ReportsController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('reports', [ReportsController::class, 'index'])->name('reports.index');
    Route::get('reports/sales', [ReportsController::class, 'sales'])->name('reports.sales');
    Route::get('reports/financial', [ReportsController::class, 'financial'])->name('reports.financial');
    Route::get('reports/customers', [ReportsController::class, 'customers'])->name('reports.customers');
    Route::get('reports/suppliers', [ReportsController::class, 'suppliers'])->name('reports.suppliers');
    Route::get('reports/inventory', [ReportsController::class, 'inventory'])->name('reports.inventory');
    Route::get('reports/employees', [ReportsController::class, 'employees'])->name('reports.employees');
});

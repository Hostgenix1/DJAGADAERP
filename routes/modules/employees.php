<?php

use App\Http\Controllers\EmployeeController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('employees/data', [EmployeeController::class, 'datatable'])->name('employees.datatable');
    Route::resource('employees', EmployeeController::class)->except(['show']);
});
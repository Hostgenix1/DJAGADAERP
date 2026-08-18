<?php

use App\Http\Controllers\PayrollController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('payroll/data', [PayrollController::class, 'datatable'])->name('payroll.datatable');
    Route::get('payroll/{payrollEntry}/payslip', [PayrollController::class, 'payslip'])->name('payroll.payslip');
    Route::resource('payroll', PayrollController::class)->except(['show']);
});
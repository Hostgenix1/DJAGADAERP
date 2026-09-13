<?php

use App\Http\Controllers\PayrollController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('payroll/data', [PayrollController::class, 'datatable'])->name('payroll.datatable');
    Route::get('payroll/autofill', [PayrollController::class, 'autofill'])->name('payroll.autofill');
    Route::get('payroll/export', [PayrollController::class, 'export'])->name('payroll.export');
    Route::get('payroll/{payrollEntry}/payslip', [PayrollController::class, 'payslip'])->name('payroll.payslip');
    Route::post('payroll/{payrollEntry}/mark-paid', [PayrollController::class, 'markPaid'])->name('payroll.mark-paid');
    Route::resource('payroll', PayrollController::class)->except(['show'])->parameters(['payroll' => 'payrollEntry']);
});

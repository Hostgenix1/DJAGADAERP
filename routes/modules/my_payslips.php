<?php

use App\Http\Controllers\MyPayslipController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('my-payslips', [MyPayslipController::class, 'index'])->name('my-payslips.index');
    Route::get('my-payslips/{payrollEntry}/download', [MyPayslipController::class, 'download'])->name('my-payslips.download');
});

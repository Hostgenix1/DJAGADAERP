<?php

use App\Http\Controllers\AttendanceController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('attendance/data', [AttendanceController::class, 'datatable'])->name('attendance.datatable');
    Route::resource('attendance', AttendanceController::class)->except(['show']);
});
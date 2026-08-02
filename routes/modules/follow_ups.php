<?php

use App\Http\Controllers\FollowUpController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('follow_ups', FollowUpController::class)->except('show');
    Route::get('follow_ups/data', [FollowUpController::class, 'datatable'])->name('follow_ups.datatable');
});

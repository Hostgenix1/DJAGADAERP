<?php

use App\Http\Controllers\CommunicationController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('communications', CommunicationController::class)->except('show');
    Route::get('communications/data', [CommunicationController::class, 'datatable'])->name('communications.datatable');
});

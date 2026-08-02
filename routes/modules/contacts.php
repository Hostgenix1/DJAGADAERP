<?php

use App\Http\Controllers\ContactController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('contacts', ContactController::class)->except('show');
    Route::get('contacts/data', [ContactController::class, 'datatable'])->name('contacts.datatable');
});

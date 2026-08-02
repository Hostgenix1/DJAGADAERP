<?php

use App\Http\Controllers\DocumentController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('documents', [DocumentController::class, 'index'])->name('documents.index');
    Route::get('documents/search', [DocumentController::class, 'search'])->name('documents.search');
    Route::post('documents/upload', [DocumentController::class, 'upload'])->name('documents.upload');
    Route::get('documents/{document}/download', [DocumentController::class, 'download'])->name('documents.download');
    Route::delete('documents/{document}', [DocumentController::class, 'destroy'])->name('documents.destroy');
});

<?php

use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    require __DIR__.'/dashboard.php';
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/users/data', [UserController::class, 'datatable'])->name('users.data');
    Route::resource('users', UserController::class)->except(['show']);
    Route::resource('roles', RoleController::class)->except(['show']);

    Route::get('/settings/company', [SettingsController::class, 'company'])->name('settings.company');
    Route::post('/settings/company', [SettingsController::class, 'updateCompany'])->name('settings.company.update');
    Route::get('/settings/taxes', [SettingsController::class, 'taxes'])->name('settings.taxes');
    Route::get('/settings/taxes/data', [SettingsController::class, 'taxesDatatable'])->name('settings.taxes.data');
    Route::post('/settings/taxes', [SettingsController::class, 'taxStore'])->name('settings.taxes.store');
    Route::put('/settings/taxes/{tax}', [SettingsController::class, 'taxUpdate'])->name('settings.taxes.update');
    Route::delete('/settings/taxes/{tax}', [SettingsController::class, 'taxDestroy'])->name('settings.taxes.destroy');
    Route::get('/settings/audit', [SettingsController::class, 'audit'])->name('settings.audit');
    Route::get('/settings/audit/data', [SettingsController::class, 'auditDatatable'])->name('settings.audit.data');
});

require __DIR__.'/auth.php';

foreach (glob(__DIR__.'/modules/*.php') as $moduleRoutes) {
    require $moduleRoutes;
}

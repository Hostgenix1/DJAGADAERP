<?php

use App\Http\Controllers\Api\AiPricingController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| AI API (future integration)
|--------------------------------------------------------------------------
|
| Read-only, tightly scoped endpoints for the future AI Sales Assistant.
| Requires a Sanctum token whose user has the `ai-read-prices` permission
| AND the `ai-read-prices` token ability. Responses NEVER include supplier
| costs, margins, payroll or any other confidential financial data — only
| approved selling prices that are still within their validity period.
|
*/

Route::middleware(['auth:sanctum', 'abilities:ai-read-prices'])->prefix('ai')->group(function () {
    Route::get('prices', [AiPricingController::class, 'prices'])->name('api.ai.prices');
});

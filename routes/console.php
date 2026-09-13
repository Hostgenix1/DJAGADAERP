<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Payroll due at 00:00 on the scheduled pay date (requires cron: schedule:run every minute)
Schedule::command('payroll:mark-due')->dailyAt('00:00');
Schedule::command('pricing:expire')->dailyAt('00:05');

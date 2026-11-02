<?php

namespace App\Providers;

use App\Console\Commands\DatabaseSnapshot;
use App\Console\Commands\MakeModule;
use App\Console\Commands\SendTestMail;
use App\Services\SettingsService;
use App\Support\MailConfigurator;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(SettingsService::class);

        $this->commands([
            MakeModule::class,
            SendTestMail::class,
            DatabaseSnapshot::class,
        ]);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // SMTP credentials saved in the admin panel override .env at runtime.
        // Ignored while the settings table is unavailable (e.g. fresh install).
        try {
            MailConfigurator::apply();
        } catch (\Throwable) {
            //
        }

        // Money filter: @money($amount) honours the configured number format.
        Blade::directive('money', function ($expression) {
            return "<?php echo e(\\App\\Support\\SettingsHelper::formatMoney({$expression})); ?>";
        });

        // Payslip money filter: always English format, e.g. 5,366.66.
        Blade::directive('payslipMoney', function ($expression) {
            return "<?php echo e(number_format((float) {$expression}, 2)); ?>";
        });
    }
}

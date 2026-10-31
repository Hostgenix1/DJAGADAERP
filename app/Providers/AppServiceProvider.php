<?php

namespace App\Providers;

use App\Console\Commands\MakeModule;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->commands([
            MakeModule::class,
        ]);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Money filter: @money($amount) honours the configured number format.
        Blade::directive('money', function ($expression) {
            return "<?php echo e(\\App\\Support\\SettingsHelper::formatMoney({$expression})); ?>";
        });
    }
}

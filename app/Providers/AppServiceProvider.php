<?php

namespace App\Providers;

use App\Console\Commands\MakeModule;
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
        // Money filter: @money($amount) renders 5,366.66
        \Illuminate\Support\Facades\Blade::directive('money', function ($expression) {
            return "<?php echo number_format((float) ($expression), 2); ?>";
        });
    }
}
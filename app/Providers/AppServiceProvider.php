<?php

namespace App\Providers;

use App\Helpers\SettingHelper;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Log;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton('setting', function () {
            return new SettingHelper();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Log application start
        if (app()->environment('production')) {
            Log::info('Application started', [
                'environment' => app()->environment(),
                'php_version' => PHP_VERSION,
                'laravel_version' => app()->version(),
                'time' => now()->toDateTimeString(),
                'memory_limit' => ini_get('memory_limit'),
            ]);
        }
    }
}

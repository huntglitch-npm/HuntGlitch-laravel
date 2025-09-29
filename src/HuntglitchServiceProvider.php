<?php

namespace Itpath\Huntglitch;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Log;

class HuntglitchServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        // Publish config file
        $this->publishes([
            __DIR__ . '/../config/huntglitch.php' => config_path('huntglitch.php'),
        ], 'config');

        // Warn if config/huntglitch.php is missing
        if (!file_exists(config_path('huntglitch.php'))) {
            \Log::warning('[Huntglitch] Please run: php artisan vendor:publish --provider="Itpath\\Huntglitch\\HuntglitchServiceProvider" --tag=config to publish the Huntglitch config file.');
        }
    }

    /**
     * Register any application services.
     */
    public function register()
    {
        //
    }
}

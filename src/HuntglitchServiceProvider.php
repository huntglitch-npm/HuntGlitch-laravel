<?php

namespace Itpath\Huntglitch;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;

class HuntglitchServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->registerJsLogRoute();

        // Warn if config/huntglitch.php is missing
        if (!file_exists(config_path('huntglitch.php'))) {
            Log::warning('[Huntglitch] Please run: php artisan vendor:publish --provider="Itpath\\Huntglitch\\HuntglitchServiceProvider" --tag=config to publish the Huntglitch config file.');
        }
    }

    protected function registerJsLogRoute()
    {
        Route::post('/huntglitch/js-log', [\Itpath\Huntglitch\JsLogController::class, 'receive']);
    }
}

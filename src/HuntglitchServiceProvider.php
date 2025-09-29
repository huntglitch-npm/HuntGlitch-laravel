<?php

namespace Itpath\Huntglitch;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class HuntglitchServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->registerJsLogRoute();
    }

    protected function registerJsLogRoute()
    {
        Route::post('/huntglitch/js-log', [\Itpath\Huntglitch\JsLogController::class, 'receive']);
    }
}

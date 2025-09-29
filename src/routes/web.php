<?php

use Illuminate\Support\Facades\Route;
use Itpath\Huntglitch\JsLogController;

Route::post('/huntglitch/js-log', [JsLogController::class, 'receive']);

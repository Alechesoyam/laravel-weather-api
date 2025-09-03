<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\WeatherController;

Route::get('/ping', function () {
    return response()->json(['message' => 'pong']);
});

Route::get('/weather', [WeatherController::class, 'show']);

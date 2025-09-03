<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\WeatherController;

Route::get('/weather', [WeatherController::class, 'show']); // API route
Route::view('/weather-dashboard', 'weather'); // Blade view route

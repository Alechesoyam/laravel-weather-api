<?php

namespace App\Services;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class WeatherService
{
    public function currentByCity(string $city): array
    {
        $cacheKey = "wx:current:city:" . mb_strtolower($city);
        // Cache for 5 minutes to reduce API calls
        return Cache::remember($cacheKey, now()->addMinutes(5), function () use ($city) {
            $base  = config('services.openweather.base');
            $key   = config('services.openweather.key');
            $units = config('services.openweather.units', 'metric');

            // Robust HTTP call: base URL, timeout, retries, and exceptions
            $response = Http::baseUrl($base)
                ->timeout(8)
                ->retry(2, 200)
                ->get('/data/2.5/weather', [
                    'q'     => $city,
                    'appid' => $key,
                    'units' => $units,
                ])
                ->throw(); // throws RequestException on non-2xx

            $json = $response->json();

            // Normalize important fields
            return [
                'location'   => $json['name'] ?? $city,
                'country'    => $json['sys']['country'] ?? null,
                'coordinates'=> $json['coord'] ?? null,
                'temperature'=> $json['main']['temp'] ?? null,
                'feels_like' => $json['main']['feels_like'] ?? null,
                'humidity'   => $json['main']['humidity'] ?? null,
                'pressure'   => $json['main']['pressure'] ?? null,
                'wind'       => [
                    'speed' => $json['wind']['speed'] ?? null,
                    'deg'   => $json['wind']['deg'] ?? null,
                ],
                'condition'  => $json['weather'][0]['main'] ?? null,
                'description'=> $json['weather'][0]['description'] ?? null,
                'icon'       => $json['weather'][0]['icon'] ?? null,
                'units'      => $units,
                'provider'   => 'openweathermap',
            ];
        });
    }
}

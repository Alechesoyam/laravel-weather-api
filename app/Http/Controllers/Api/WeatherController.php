<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class WeatherController extends Controller
{
    public function show(Request $request)
    {
        $cities = [
            'San Pablo' => 'San Pablo,Laguna,PH',
            'Alaminos'  => 'Alaminos,Pangasinan,PH',
            'Batangas'  => 'Batangas,PH',
            'Manila'    => 'Manila,PH'
        ];

        $cityKey = $request->query('city', 'Manila');

        if (!isset($cities[$cityKey])) {
            return response()->json(['error' => 'City not found'], 404);
        }

        $cityQuery = $cities[$cityKey];
        $apiKey = env('OPENWEATHER_API_KEY');

        if (!$apiKey) {
            return response()->json(['error' => 'API key missing'], 500);
        }

        $url = "https://api.openweathermap.org/data/2.5/weather?q={$cityQuery}&appid={$apiKey}&units=metric";

        $response = Http::get($url);

        if ($response->successful()) {
            return $response->json();
        } else {
            return response()->json(['error' => 'City not found'], 404);
        }
    }
}

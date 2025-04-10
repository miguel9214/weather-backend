<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\WeatherService;
use Illuminate\Support\Facades\Log;
use Exception;

class WeatherController extends Controller
{
    protected WeatherService $weatherService;

    public function __construct(WeatherService $weatherService)
    {
        $this->weatherService = $weatherService;
    }

    public function getWeather(Request $request)
    {
        $request->validate(['city' => 'required|string']);

        try {
            $data = $this->weatherService->getWeather($request->city);

            return response()->json([
                'message' => __('messages.weather_retrieved'),
                'data'    => $data,
            ]);
        } catch (Exception $e) {
            Log::error('Error fetching weather data: ' . $e->getMessage());

            return response()->json([
                'message' => __('messages.weather_fetch_error')
            ], 500);
        }
    }
}

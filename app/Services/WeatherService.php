<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class WeatherService
{
    protected string $baseUrl;
    protected string $apiKey;

    public function __construct()
    {
        $this->baseUrl = 'http://api.weatherapi.com/v1/current.json';
        $this->apiKey = config('services.weatherapi.key');
    }

    public function getWeather(string $city): array
    {
        $cacheKey = "weather_{$city}";

        return Cache::remember($cacheKey, now()->addMinutes(10), function () use ($city) {
            $response = Http::get($this->baseUrl, [
                'key'  => $this->apiKey,
                'q'    => $city,
                'aqi'  => 'no',
            ]);

            if (! $response->successful()) {
                throw new \Exception('Weather API error: ' . $response->body());
            }

            $data = $response->json();

            return [
                'city'       => $data['location']['name'],
                'region'     => $data['location']['region'],
                'country'    => $data['location']['country'],
                'temperature'=> $data['current']['temp_c'],
                'condition'  => $data['current']['condition']['text'],
                'icon'       => $data['current']['condition']['icon'],
                'humidity'   => $data['current']['humidity'],
                'wind_kph'   => $data['current']['wind_kph'],
                'localtime'  => $data['location']['localtime'],
            ];
        });
    }
}

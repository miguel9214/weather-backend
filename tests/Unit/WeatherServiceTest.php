<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\WeatherService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class WeatherServiceTest extends TestCase
{
    public function test_it_returns_weather_data_for_a_city()
    {
        // Datos simulados que devuelve WeatherAPI
        $fakeResponse = [
            'location' => [
                'name' => 'Paris',
                'region' => 'Ile-de-France',
                'country' => 'France',
                'localtime' => '2025-04-09 12:00',
            ],
            'current' => [
                'temp_c' => 20.5,
                'condition' => [
                    'text' => 'Sunny',
                    'icon' => '//cdn.weatherapi.com/weather/64x64/day/113.png',
                ],
                'humidity' => 50,
                'wind_kph' => 15.2,
            ],
        ];

        Http::fake([
            '*' => Http::response($fakeResponse, 200),
        ]);

        Cache::shouldReceive('remember')
            ->once()
            ->andReturnUsing(function ($key, $ttl, $callback) {
                return $callback();
            });

        $weatherService = new WeatherService();
        $weather = $weatherService->getWeather('Paris');

        $this->assertEquals('Paris', $weather['city']);
        $this->assertEquals('France', $weather['country']);
        $this->assertEquals('Sunny', $weather['condition']);
    }
}


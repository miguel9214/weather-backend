<?php

namespace Database\Factories;

use App\Models\Search;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class SearchFactory extends Factory
{
    protected $model = Search::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(), // Esto crea automáticamente un nuevo usuario relacionado
            'city' => $this->faker->city(),
            'data' => [
                'location' => [
                    'name' => $this->faker->city(),
                    'region' => $this->faker->state(),
                    'country' => $this->faker->country(),
                    'localtime' => now()->format('Y-m-d H:i:s'),
                ],
                'current' => [
                    'temp_c' => $this->faker->randomFloat(1, -10, 40),
                    'condition' => [
                        'text' => $this->faker->word(),
                        'icon' => 'https://cdn.weatherapi.com/weather/64x64/day/116.png',
                    ],
                    'humidity' => $this->faker->numberBetween(20, 100),
                    'wind_kph' => $this->faker->randomFloat(1, 0, 100),
                ]
            ],
            'favorite' => $this->faker->boolean(20), // 20% de ser favorito
        ];
    }
}

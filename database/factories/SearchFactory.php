<?php

// database/factories/SearchFactory.php

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
            'user_id' => User::factory(),
            'city' => $this->faker->city(),
            'data' => [
                'location' => [
                    'name' => $this->faker->city(),
                    'region' => $this->faker->state(),
                    'country' => $this->faker->country(),
                    'localtime' => now()->toDateTimeString(),
                ],
                'current' => [
                    'temp_c' => $this->faker->randomFloat(1, -10, 40),
                    'condition' => [
                        'text' => $this->faker->word(),
                        'icon' => 'https://cdn.weatherapi.com/icon.png',
                    ],
                    'humidity' => $this->faker->numberBetween(20, 100),
                    'wind_kph' => $this->faker->randomFloat(1, 0, 100),
                ]
            ],
            'favorite' => false,
        ];
    }
}

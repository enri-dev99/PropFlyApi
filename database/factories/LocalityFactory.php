<?php

namespace Database\Factories;

use App\Models\Locality;
use App\Models\City;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Locality>
 */
class LocalityFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->word(),
            'city_id' => City::factory()
        ];
    }
}

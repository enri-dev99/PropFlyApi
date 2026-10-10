<?php

namespace Database\Factories;

use App\Models\Property;
use App\Models\User;
use App\Enums\PropertyType;
use App\Enums\PropertyOperation;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Locality;

/**
 * @extends Factory<Property>
 */
class PropertyFactory extends Factory
{
    protected $model = Property::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(), 
            'title' => fake()->sentence(4),
            'description' => fake()->paragraph(),
            'price' => fake()->randomFloat(2, 50000, 500000),
            'type' => fake()->randomElement(PropertyType::cases())->value,
            'operation' => fake()->randomElement(PropertyOperation::cases())->value,
            'bedrooms' => fake()->numberBetween(1, 5),
            'surface' => fake()->numberBetween(40, 300),
            'locality_id' => Locality::factory(),
            'address' => fake()->address(),
            'postal_code' => fake()->numerify('#####')
        ];
    }
}

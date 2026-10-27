<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\User;
use App\Models\Property;
use App\Enums\PropertyType;
use Tests\TestCase;

class PropertyFiltersTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_filter_properties_by_type(): void
    {
        $user = User::factory()->create();

        $apartment = Property::factory()->create([
            'type' => PropertyType::APARTMENT,
            'price' => 150000,
        ]);

        Property::factory()->create([
            'type' => PropertyType::HOUSE,
            'price' => 200000,
        ]);

        $response = $this->actingAs($user)->get('/api/v1/properties?type=0');

        $response->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.id', $apartment->id);
    }
    public function test_user_can_filter_properties_by_minimun_price(): void
    {
        $user = User::factory()->create();

        Property::factory()->create([
            'price' => 50000
        ]);


        $expensiveProperty = Property::factory()->create([
            'price' => 150000,
        ]);

        $response = $this->actingAs($user)->get('/api/v1/properties?min_price=100000');

        $response->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.id', $expensiveProperty->id);
    }

    public function test_user_can_filter_properties_by_maximum_price(): void
    {
        $user = User::factory()->create();

        $affordableProperty = Property::factory()->create([
            'price' => 100000,
        ]);

        Property::factory()->create([
            'price' => 200000,
        ]);

        $response = $this->actingAs($user)->get('/api/v1/properties?max_price=150000');

        $response->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.id', $affordableProperty->id);
    }

    public function test_user_can_combine_multiple_filters(): void
    {
        $user = User::factory()->create();

        $matchingProperty = Property::factory()->create([
            'type' => PropertyType::APARTMENT,
            'operation' => 0,
            'price' => 150000,
        ]);

        Property::factory()->create([
            'type' => PropertyType::HOUSE,
            'operation' => 0,
            'price' => 150000,
        ]);

        Property::factory()->create([
            'type' => PropertyType::APARTMENT,
            'operation' => 1,
            'price' => 150000,
        ]);

        Property::factory()->create([
            'type' => PropertyType::APARTMENT,
            'operation' => 0,
            'price' => 300000,
        ]);

        $response = $this->actingAs($user)->getJson(
            '/api/v1/properties?type=0&operation=0&min_price=100000&max_price=200000'
        );

        $response->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.id', $matchingProperty->id);
    }
}

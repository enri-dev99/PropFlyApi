<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Locality;
use Tests\TestCase;
use App\Http\Resources\PropertyResource;
use App\Enums\PropertyType;
use App\Enums\PropertyOperation;
use App\Models\User;
use App\Enums\UserRole;
use App\Models\Property;

class PropertyAuthorizationTest extends TestCase
{
    // Reinicia la base de datos entre pruebas para mantenerlas aisladas.
    use RefreshDatabase;
    public function test_admin_cannot_create_property(): void
    {
        $admin = User::factory()->create([
            'role' => UserRole::ADMIN,
        ]);
        $locality = Locality::factory()->create();
        $this->actingAs($admin, 'sanctum')
            ->postJson('/api/v1/properties', [
                'title' => 'Test Property',
                'description' => 'Test Description',
                'price' => 100000,
                'type' => PropertyType::HOUSE,
                'operation' => PropertyOperation::RENT,
                'bedrooms' => 2,
                'surface' => 100,
                'locality_id' => $locality->id,
                'address' => 'Test Address',
                'postal_code' => '12345'    
            ])
            ->assertForbidden();

        $this->assertDatabaseCount('properties', 0);
    }

    // Comprueba que un usuario autenticado puede consultar el listado.
    public function test_authenticated_user_can_list_properties(): void
    {

        $user = User::factory()->create();

        Property::factory()->count(3)->create();

        $response = $this->actingAs($user)->getJson('/api/v1/properties');

        $response->assertStatus(200);

        $response->assertJsonCount(3, 'data');
    }

    // Verifica que el listado devuelve el título guardado para una propiedad.
    public function test_property_title(): void
    {

        $user = User::factory()->create();
        Property::factory()->create([
            'title' => 'Piso en Madrid',
        ]);

        $response = $this->actingAs($user)->getJson('/api/v1/properties');

        $response->assertStatus(200);
        $response->assertJsonCount(1, 'data');
        $response->assertJsonPath('data.0.title', 'Piso en Madrid');
    }

    // Comprueba que un usuario autenticado puede ver el detalle de una propiedad.
    public function test_authenticated_user_can_view_a_property(): void
    {
        $user = User::factory()->create();
        $property = Property::factory()->create();

        $response = $this->actingAs($user)->getJson("/api/v1/properties/{$property->id}");

        $response->assertStatus(200);
        $response->assertJsonPath('data.id', $property->id);
    }

    // Impide que un usuario modifique una propiedad perteneciente a otra persona.
    public function test_user_cannot_update_a_property(): void
    {
        $user = User::factory()->create();

        $propertyOwner = User::factory()->create();
        $property = Property::factory()->create([
            'user_id' => $propertyOwner->id,
        ]);

        $response = $this->actingAs($user)->putJson("/api/v1/properties/{$property->id}", [
            'title' => 'Updated Title',
        ]);

        $response->assertStatus(403);
    }

    // Verificar que el propietario puede actualizar su propia propiedad.
    public function test_user_can_update_own_property(): void
    {
        $user = User::factory()->create();
        $property = Property::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->putJson("/api/v1/properties/{$property->id}", [
            'title' => 'Updated Title',
        ]);

        $response->assertStatus(200);
        $response->assertJsonPath('data.title', 'Updated Title');
    }

    public  function test_admin_can_update_another_users_property(): void
    {
        $admin = User::factory()->create([
            'role' => UserRole::ADMIN,
        ]);
        $propertyOwner = User::factory()->create();

        $property = Property::factory()->create([
            'user_id' => $propertyOwner->id,
        ]);

        $response = $this->actingAs($admin)->putJson("/api/v1/properties/{$property->id}", [
            'title' => 'Admin Updated Title',
        ]);

        $response->assertStatus(200);
        $response->assertJsonPath('data.title', 'Admin Updated Title');
    }
    public function test_user_can_delete_own_property(): void
    {
        $user = User::factory()->create();
        $property = Property::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->deleteJson("/api/v1/properties/{$property->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('properties', [
            'id' => $property->id,
        ]);
    }

    public function test_user_cannot_delete_another_users_property(): void
    {
        $user = User::factory()->create();

        $propertyOwner = User::factory()->create();
        $property = Property::factory()->create([
            'user_id' => $propertyOwner->id,
        ]);

        $response = $this->actingAs($user)->deleteJson("/api/v1/properties/{$property->id}");

        $response->assertStatus(403);
    }

    public function test_admin_can_delete_another_users_property(): void
    {

        $admin = User::factory()->create([
            'role' => UserRole::ADMIN,
        ]);
        $propertyOwner = User::factory()->create();

        $property = Property::factory()->create([
            'user_id' => $propertyOwner->id,
        ]);

        $response = $this->actingAs($admin)->deleteJson("/api/v1/properties/{$property->id}");

        $response->assertStatus(200);

        $this->assertDatabaseMissing('properties', [
            'id' => $property->id,
        ]);
    }
    public function test_guest_cannot_create_property(): void
    {
        $response = $this->postJson("/api/v1/properties", [
            'title' => 'Guest Property',
        ]);

        $response->assertStatus(401);
    }
    public function test_authenticated_user_can_create_property(): void
    {
        $user = User::factory()->create();

        $existingProperty = Property::factory()->create();

        $response = $this->actingAs($user)->postJson(
            '/api/v1/properties',
            [
                'title' => 'Piso de prueba',
                'description' => 'Piso luminoso para realizar tests.',
                'price' => 180000,
                'type' => PropertyType::APARTMENT,
                'operation' => PropertyOperation::SALE,
                'bedrooms' => 2,
                'bathrooms' => 1,
                'surface' => 75,
                'locality_id' => $existingProperty->locality_id,
                'address' => 'Calle de prueba 10',
                'postal_code' => '28001',
            ]
        );

        $response->assertSuccessful();

        $this->assertDatabaseHas('properties', [
            'title' => 'Piso de prueba',
            'price' => 180000,
            'user_id' => $user->id,
        ]);
    }

    public function test_property_is_assigned_to_authenticated_user(): void
    {
        $user = User::factory()->create();
        $anotherUser = User::factory()->create();

        $existingProperty = Property::factory()->create();

        $response = $this->actingAs($user)->postJson(
            '/api/v1/properties',
            [
                'title' => 'Propiedad con propietario correcto',
                'description' => 'Test de asignación de propietario.',
                'price' => 200000,
                'type' => PropertyType::APARTMENT,
                'operation' => PropertyOperation::SALE,
                'bedrooms' => 3,
                'bathrooms' => 2,
                'surface' => 90,
                'locality_id' => $existingProperty->locality_id,
                'address' => 'Calle de prueba 20',
                'postal_code' => '28002',
                'user_id' => $anotherUser->id,
            ]
        );

        $response->assertSuccessful();

        $this->assertDatabaseHas('properties', [
            'title' => 'Propiedad con propietario correcto',
            'user_id' => $user->id,
        ]);

        $this->assertDatabaseMissing('properties', [
            'title' => 'Propiedad con propietario correcto',
            'user_id' => $anotherUser->id,
        ]);
    }

    public function test_user_cannot_view_nonexistent_property(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->getJson('/api/v1/properties/9999999999999');

        $response->assertNotFound();
    }

    public function test_user_cannot_update_nonexistent_property(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->putJson(
            '/api/v1/properties/9999999999999',
            ['title' => 'Título actualizado']
        );

        $response->assertNotFound();
    }
    public function test_user_cannot_delete_nonexistent_property(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->deleteJson('/api/v1/properties/9999999999999');

        $response->assertNotFound();
    }

    public function test_property_creation_fails_with_negative_price(): void
    {
        $user = User::factory()->create();

        $existingProperty = Property::factory()->create();

        $response = $this->actingAs($user)->postJson(
            '/api/v1/properties',
            [
                'title' => 'Propiedad inválida',
                'description' => 'Test de validación.',
                'price' => -100,
                'type' => PropertyType::APARTMENT,
                'operation' => PropertyOperation::SALE,
                'bedrooms' => 2,
                'bathrooms' => 1,
                'surface' => 75,
                'locality_id' => $existingProperty->locality_id,
                'address' => 'Calle de prueba 10',
                'postal_code' => '28001',
            ]
        );

        $response->assertUnprocessable();

        $this->assertDatabaseMissing('properties', [
            'title' => 'Propiedad inválida',
        ]);
    }
}

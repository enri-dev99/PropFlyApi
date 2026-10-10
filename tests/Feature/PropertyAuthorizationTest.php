<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Property;
use App\Models\User;
use App\Enums\UserRole;

class PropertyAuthorizationTest extends TestCase
{
    // Reinicia la base de datos entre pruebas para mantenerlas aisladas.
    use RefreshDatabase;
  
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
}

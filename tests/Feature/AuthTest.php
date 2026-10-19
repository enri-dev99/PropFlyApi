<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register(): void
    {
        $email = fake()->unique()->safeEmail();
        $response = $this->postJson('/api/v1/auth/register', [
            'name' => 'John',
            'surname' => 'Doe',
            'email' => $email,
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);

        $response->assertCreated()->assertJsonStructure([
            'user',
            'token'
        ])->assertJsonPath('user.email', $email);
    }

    public function test_register_fails_with_duplicate_email(): void
    {
        $email = fake()->unique()->safeEmail();
        User::factory()->create([
            'email' => $email,
        ]);

        $response = $this->postJson('/api/v1/auth/register', [
            'name' => 'John',
            'surname' => 'Doe',
            'email' => $email,
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);

        $response->assertUnprocessable()->assertJsonValidationErrors(['email']);
    }

    public function test_register_fails_without_password_confirmation(): void
    {
        $email = fake()->unique()->safeEmail();
        $response = $this->postJson('/api/v1/auth/register', [
            'name' => 'John',
            'surname' => 'Doe',
            'email' => $email,
            'password' => 'Password123!',
        ]);

        $response->assertUnprocessable()->assertJsonValidationErrors(['password']);
    }

    public function test_user_can_login(): void
    {
        $password = 'Password123!';
        $user = User::factory()->create([
            'password' => Hash::make($password),
        ]);

        $response = $this->postJson('/api/v1/auth/login', [
            'email' => $user->email,
            'password' => $password,
        ]);

        $response->assertOk()->assertJsonStructure([
            'user',
            'token'
        ])->assertJsonPath('user.email', $user->email);
    }

    public function test_login_fails_with_nonexistent_email(): void
    {
        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'nonexistent@example.com',
            'password' => 'Password123!',
        ]);

        $response->assertUnauthorized();
    }

    
    public function test_user_can_logout(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/v1/auth/logout');

        $response->assertOk();

        $this->assertDatabaseMissing('personal_access_tokens', [
            'tokenable_id' => $user->id,
            'tokenable_type' => User::class,
        ]);
    }

    public function test_revoked_token_cannot_be_used_again(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken;

  
        $user->tokens()->delete();

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/v1/auth/logout');

        $response->assertUnauthorized();
    }
}

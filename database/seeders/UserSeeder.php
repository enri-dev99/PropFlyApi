<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Admin',
            'surname' => 'PropFly',
            'email' => 'admin@propfly.test',
            'role' => UserRole::ADMIN,
        ]);

        User::factory()->count(20)->create();
    }
}

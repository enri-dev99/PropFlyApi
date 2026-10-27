<?php

namespace Database\Seeders;

use App\Models\Locality;
use App\Models\Property;
use App\Models\User;
use Illuminate\Database\Seeder;

class PropertySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::query()->get();
        $localities = Locality::query()->get();

        if ($users->isEmpty() || $localities->isEmpty()) {
            return;
        }

        Property::factory()
            ->count(50)
            ->state(fn () => [
                'user_id' => $users->random()->id,
                'locality_id' => $localities->random()->id,
            ])
            ->create();
    }
}

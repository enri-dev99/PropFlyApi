<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Collection;
use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    public function all(): Collection
    {
        return User::all();
    }

    public function find(int $id): ?User
    {
        return User::find($id);
    }

    public function findByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    public function create(array $data): User
    {
        return User::create($data);
    }

    public function update(int $id, array $data): ?User
    {
        $user = User::find($id);
        if (!$user) {
            return null;
        }
        $user->update($data);
        return $user;
    }

    public function delete(int $id): bool
    {
        $user = User::find($id);
        if (!$user) {
            return false;
        }
        return (bool)$user->delete();
    }
}

<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Services\Contracts\UserServiceInterface;
use Illuminate\Database\Eloquent\Collection;

class UserService implements UserServiceInterface
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ){}

    public function list(): Collection
    {
        return $this->userRepository->all();
    }
    public function find(int $id): ?User
    {
        return $this->userRepository->find($id);
    }

    public function create(array $data): User
    {
        return $this->userRepository->create($data);
    }

    public function update(int $id, array $data): ?User
    {
        return $this->userRepository->update($id, $data);
    }

    public function delete(int $id): bool
    {
        return $this->userRepository->delete($id);
    }

    public function updateRole(int $id, string $role): ?User    
    {
        return $this->userRepository->updateRole($id, $role);
    }
}
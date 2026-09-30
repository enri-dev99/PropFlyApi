<?php

namespace App\Services\Contracts;

use App\Models\Property;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

interface PropertyServiceInterface
{
    public function list(array $filters = [], int $perPage = 15): LengthAwarePaginator;

    public function find(int $id): ?Property;

    public function store(array $data, User $user): Property;

    public function update(int $id, array $data): ?Property;

    public function delete(int $id): bool;
}

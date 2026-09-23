<?php

namespace App\Repositories\Contracts;

use App\Models\Property;
use Illuminate\Pagination\LengthAwarePaginator;

interface PropertyRepositoryInterface
{
    public function all(array $filters = [], int $perPage = 15): LengthAwarePaginator;

    public function find(int $id): ?Property;

    public function create(array $data): Property;

    public function update(int $id, array $data): ?Property;

    public function delete(int $id): bool;
}

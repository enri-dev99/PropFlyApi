<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;
use App\Models\Locality;

interface LocalityRepositoryInterface
{
    public function all(): Collection;

    public function find(int $id): ?Locality;

    public function create(array $data): Locality;

    public function update(int $id, array $data): ?Locality;

    public function delete(int $id): bool;
}

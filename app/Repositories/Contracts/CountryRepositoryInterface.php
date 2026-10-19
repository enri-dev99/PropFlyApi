<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;
use App\Models\Country;

interface CountryRepositoryInterface
{
    public function all(): Collection;

    public function find(int $id): ?Country;

    public function create(array $data): Country;

    public function update(int $id, array $data): ?Country;

    public function delete(int $id): bool;
}

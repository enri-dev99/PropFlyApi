<?php

namespace App\Services\Contracts;

use Illuminate\Support\Collection;
use App\Models\Country;

interface CountryServiceInterface
{
    public function list(): Collection;

    public function find(int $id): ?Country;

    public function store(array $data): Country;

    public function update(int $id, array $data): ?Country;

    public function delete(int $id): bool;
}

<?php

namespace App\Services\Contracts;

use App\Models\City;
use Illuminate\Database\Eloquent\Collection;

interface CityServiceInterface
{
    public function list(): Collection;

    public function find(int $id): ?City;

    public function store(array $data): City;

    public function update(int $id, array $data): ?City;

    public function delete(int $id): bool;
}
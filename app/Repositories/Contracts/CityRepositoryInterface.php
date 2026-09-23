<?php

namespace App\Repositories\Contracts;

use App\Models\City;
use Illuminate\Database\Eloquent\Collection;

interface CityRepositoryInterface
{
    public function all(): Collection;

    public function find(int $id): ?City;

    public function create(array $data): City;

    public function update(int $id, array $data): ?City;
    
    public function delete(int $id): bool;

}
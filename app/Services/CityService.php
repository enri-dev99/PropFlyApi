<?php

namespace App\Services;

use App\Models\City;
use App\Repositories\Contracts\CityRepositoryInterface;
use App\Services\Contracts\CityServiceInterface;
use Illuminate\Database\Eloquent\Collection;

class CityService implements CityServiceInterface
{
    public function __construct(
        protected CityRepositoryInterface $cityRepository
    ){}
    public function list(): Collection
    {
        return $this->cityRepository->all();
    }
    public function find(int $id): ?City
    {
        return $this->cityRepository->find($id);
    }
    public function store(array $data): City
    {
        return $this->cityRepository->create($data);
    }

    public function update(int $id, array $data): ?City
    {
        return $this->cityRepository->update($id, $data);
    }

    public function delete(int $id): bool
    {
        return $this->cityRepository->delete($id);
    }
}
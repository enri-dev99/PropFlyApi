<?php

namespace App\Services;

use App\Services\Contracts\CountryServiceInterface;
use App\Repositories\Contracts\CountryRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Country;

class CountryService implements CountryServiceInterface
{
    public function __construct(
        protected CountryRepositoryInterface $countryRepository
    ) {}

    public function list(): Collection
    {
        return $this->countryRepository->all();
    }

    public function find(int $id): ?Country
    {
        return $this->countryRepository->find($id);
    }

    public function store(array $data): Country
    {
        return $this->countryRepository->create($data);
    }

    public function update(int $id, array $data): ?Country
    {
        return $this->countryRepository->update($id, $data);
    }

    public function delete(int $id): bool
    {
        return $this->countryRepository->delete($id);
    }
}

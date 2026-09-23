<?php

namespace App\Services;

use App\Services\Contracts\LocalityServiceInterface;
use App\Repositories\Contracts\LocalityRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Locality;

class LocalityService implements LocalityServiceInterface
{
    public function __construct(
        protected LocalityRepositoryInterface $localityRepository
        ) {}
        public function list(): Collection
        {
            return $this->localityRepository->all();
        }

        public function find(int $id): ?Locality
        {
            return $this->localityRepository->find($id);
        }

        public function store(array $data): Locality
        {
            return $this->localityRepository->create($data);
        }

        public function update(int $id, array $data): ?Locality
        {
            return $this->localityRepository->update($id, $data);
        }

        public function delete(int $id): bool
        {
            return $this->localityRepository->delete($id);
        }
}
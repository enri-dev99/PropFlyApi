<?php

namespace App\Services;
use App\Models\User;
use App\Models\Property;
use App\Repositories\Contracts\PropertyRepositoryInterface;
use App\Services\Contracts\PropertyServiceInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class PropertyService implements PropertyServiceInterface
{
    public function __construct(
        protected PropertyRepositoryInterface $propertyRepository
    ) {}

    public function list(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->propertyRepository->all($filters, $perPage);
    }

    public function find(int $id): ?Property
    {
        return $this->propertyRepository->find($id);
    }

    public function store(array $data, User $user): Property
    {
        $data['user_id'] = $user->id; 
        return $this->propertyRepository->create($data);
    }

    public function update(int $id, array $data): ?Property
    {
        return $this->propertyRepository->update($id, $data);
    }

    public function delete(int $id): bool
    {
        return $this->propertyRepository->delete($id);
    }
}

<?php

namespace App\Repositories;

use App\Models\Property;
use App\Repositories\Contracts\PropertyRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class PropertyRepository implements PropertyRepositoryInterface
{
    public function all(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = Property::with(['locality.city']);

        if(isset($filters['type'])){
            $query->where('type', $filters['type']);
        }
        if(isset($filters['operation'])){
            $query->where('operation', $filters['operation']);
        }
        if(isset($filters['min_price'])){
            $query->where('price', '>=', $filters['min_price']);
        }
        if(isset($filters['max_price'])){
            $query->where('price', '<=', $filters['max_price']);
        }
        if(isset($filters['locality_id'])){
            $query->where('locality_id', $filters['locality_id']);
        }
        if(isset($filters['city_id'])){
            $query->whereHas('locality', function ($query) use ($filters){
                $query->where('city_id', $filters['city_id']);
            });
        }

        return $query->paginate($perPage);

    }

    public function find(int $id): ?Property
    {
        return Property::find($id);
    }

    public function create(array $data): Property
    {
        return Property::create($data);
    }

    public function update(int $id, array $data): ?Property
    {
        $property = Property::find($id);

        if (! $property) {
            return null;
        }

        $property->update($data);

        return $property;
    }

    public function delete(int $id): bool
    {
        $property = Property::find($id);

        if (! $property) {
            return false;
        }

        return (bool) $property->delete();
    }
}

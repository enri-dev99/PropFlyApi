<?php

namespace App\Repositories;

use App\Models\City;
use App\Repositories\Contracts\CityRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class CityRepository implements CityRepositoryInterface
 {
    public function all(): Collection
    {
        return City::all();
    }

    public function find(int $id): ?City
    {
        return City::find($id);
    }

    public function create(array $data): City
    {
        return City::create($data);
    }

    public function update(int $id, array $data): ?City
    {
        $city = City::find($id);
        if (!$city) {
            return null;
        }
        $city->update($data);

        return $city;
    }

    public function delete(int $id): bool
    {
        $city = City::find($id);
        if (!$city) {
            return false;
        }
        return (bool)$city->delete();
    }
 }
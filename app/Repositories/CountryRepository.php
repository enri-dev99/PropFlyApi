<?php

namespace App\Repositories;

use App\Repositories\Contracts\CountryRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Country;

class CountryRepository implements CountryRepositoryInterface
{
    public function all(): Collection
    {
        return Country::all();
    }

    public function find(int $id): ?Country
    {
        return Country::find($id);
    }

    public function create(array $data): Country
    {
        return Country::create($data);
    }

    public function update(int $id, array $data): ?Country
    {
        $country = Country::find($id);
        if (! $country) {
            return null;
        }
        $country->update($data);
        return $country;
    }

    public function delete(int $id): bool
    {
        $country = Country::find($id);
        if (! $country) {
            return false;
        }
        return (bool) $country->delete();
    }
}

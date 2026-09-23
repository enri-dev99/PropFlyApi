<?php
namespace App\Repositories;

use App\Repositories\Contracts\LocalityRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Locality;

class LocalityRepository implements LocalityRepositoryInterface
{
    public function all(): Collection
    {
        return Locality::all();
    }

    public function find(int $id): ?Locality
    {
        return Locality::find($id);
    }

    public function create(array $data): Locality
    {
        return Locality::create($data);
    }

    public function update(int $id, array $data): ?Locality
    {
        $locality = Locality::find($id);
        if (!$locality) {
            return null;
        }
        $locality->update($data);
        return $locality;
    }

    public function delete(int $id): bool
    {
        $locality = Locality::find($id);
        if (!$locality) {
            return false;
        }
        return (bool) $locality->delete();
    }
}

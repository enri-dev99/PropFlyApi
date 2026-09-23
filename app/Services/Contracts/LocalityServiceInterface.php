<?php
namespace App\Services\Contracts;

use Illuminate\Support\Collection;
use App\Models\Locality;

interface LocalityServiceInterface
{
    public function list(): Collection;

    public function find(int $id): ?Locality;

    public function store(array $data): Locality;

    public function update(int $id, array $data): ?Locality;
    
    public function delete(int $id): bool;
}
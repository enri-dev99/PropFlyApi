<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLocalityRequest;
use App\Http\Requests\UpdateLocalityRequest;
use App\Services\Contracts\LocalityServiceInterface;

class LocalityController extends Controller
{
    public function __construct(
        protected LocalityServiceInterface $localityService
    ) {}

    public function index()
    {
        return response()->json($this->localityService->list());
    }

    public function show(int $id)
    {
        $locality = $this->localityService->find($id);

        if (! $locality) {
            return response()->json(['message' => 'Locality not found'], 404);
        }

        return response()->json($locality);
    }

    public function store(StoreLocalityRequest $request)
    {
        return response()->json($this->localityService->store($request->validated()), 201);
    }

    public function update(UpdateLocalityRequest $request, int $id)
    {
        $locality = $this->localityService->update($id, $request->validated());

        if (! $locality) {
            return response()->json(['message' => 'Locality not found'], 404);
        }

        return response()->json($locality);
    }
    public function destroy(int $id)
    {
        $deleted = $this->localityService->delete($id);

        if (! $deleted) {
            return response()->json(['message' => 'Locality not found'], 404);
        }

        return response()->json(['message' => 'Locality deleted']);
    }
}

<?php

namespace App\Http\Controllers;

use App\Services\Contracts\PropertyServiceInterface;
use App\Http\Resources\PropertyResource;
use Illuminate\Http\Request;
use App\Http\Requests\StorePropertyRequest;
use App\Http\Requests\UpdatePropertyRequest;

class PropertyController extends Controller
{
    public function __construct(
        protected PropertyServiceInterface $propertyService
    ) {}

    public function index(Request $request){
        $filters = $request->only(['type', 'operation', 'min_price', 'max_price', 'locality_id', 'city_id']);
        $perPage = min(
            $request->integer('per_page', 15),
            50
        );
        $properties = $this->propertyService->list($filters, $perPage);
        return PropertyResource::collection($properties);
    }

    public function show(int $id){
        $property = $this->propertyService->find($id);

        if (!$property) {
            return response()->json(['message' => 'Property not found'], 404);
        }

        return new PropertyResource($property);
    }

    public function store(StorePropertyRequest $request){
        $property = $this->propertyService->store($request->validated());
        return new PropertyResource($property)->response()->setStatusCode(201);
    }

    public function update(UpdatePropertyRequest $request, int $id){
        $property = $this->propertyService->update($id, $request->validated());

        if (!$property) {
            return response()->json(['message' => 'Property not found'], 404);
        }

        return new PropertyResource($property);
    }

    public function destroy(int $id){
        $deleted = $this->propertyService->delete($id);

        if (! $deleted) {
            return response()->json(['message' => 'Property not found'], 404);
        }

        return response()->json(['message' => 'Property deleted']);
    }
}

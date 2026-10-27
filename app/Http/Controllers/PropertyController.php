<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Services\Contracts\PropertyServiceInterface;
use App\Http\Resources\PropertyResource;
use Illuminate\Http\Request;
use App\Http\Requests\StorePropertyRequest;
use App\Http\Requests\UpdatePropertyRequest;
use Illuminate\Support\Facades\Gate;

class PropertyController extends Controller
{
    public function __construct(
        protected PropertyServiceInterface $propertyService
    ) {}

    public function index(Request $request)
    {
        $filters = $request->only(['type', 'operation', 'min_price', 'max_price', 'locality_id', 'city_id', 'sort_by', 'sort_direction']);
        $perPage = min(
            $request->integer('per_page', 15),
            50
        );
        $properties = $this->propertyService->list($filters, $perPage);
        return PropertyResource::collection($properties);
    }

    public function show(Property $property)
    {
        return new PropertyResource($property);
    }

    public function store(StorePropertyRequest $request)
    {
        $property = $this->propertyService->store(
            $request->validated(), 
            $request->user()
        );

        $resource = new PropertyResource($property);

        return $resource->response()->setStatusCode(201);
    }

    public function update(UpdatePropertyRequest $request, Property $property)
    {
      
        Gate::authorize('update', $property);

        $property = $this->propertyService->update(
            $property->id, 
            $request->validated()
        );

        return new PropertyResource($property);
    }

    public function destroy(Property $property)
    {
       
        Gate::authorize('delete', $property);

        $this->propertyService->delete($property->id);


        return response()->json(['message' => 'Property deleted']);
    }
}

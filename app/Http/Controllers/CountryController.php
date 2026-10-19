<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCountryRequest;
use App\Http\Requests\UpdateCountryRequest;
use App\Services\Contracts\CountryServiceInterface;

class CountryController extends Controller
{
    public function __construct(
        protected CountryServiceInterface $countryService
    ) {}

    public function index()
    {
        return response()->json($this->countryService->list());
    }

    public function show(int $id)
    {
        $country = $this->countryService->find($id);

        if (! $country) {
            return response()->json(['message' => 'Country not found'], 404);
        }

        return response()->json($country);
    }

    public function store(StoreCountryRequest $request)
    {
        return response()->json($this->countryService->store($request->validated()), 201);
    }

    public function update(UpdateCountryRequest $request, int $id)
    {
        $country = $this->countryService->update($id, $request->validated());

        if (! $country) {
            return response()->json(['message' => 'Country not found'], 404);
        }

        return response()->json($country);
    }

    public function destroy(int $id)
    {
        $deleted = $this->countryService->delete($id);

        if (! $deleted) {
            return response()->json(['message' => 'Country not found'], 404);
        }

        return response()->json(['message' => 'Country deleted']);
    }
}

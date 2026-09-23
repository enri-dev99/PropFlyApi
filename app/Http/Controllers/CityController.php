<?php

namespace App\Http\Controllers;

use App\Services\Contracts\CityServiceInterface;
use Illuminate\Http\Request;
use App\Http\Requests\StoreCityRequest;
use App\Http\Requests\UpdateCityRequest;


class CityController extends Controller
{
    public function __construct(
        protected CityServiceInterface $cityService
    ){}

    public function index(){
        return $this->cityService->list();
    }

    public function show(int $id){
        $city = $this->cityService->find($id);

        if(! $city)
            return response()->json(['message' => 'City not found'], 404);

        return response()->json($city);
    }
    
    public function store(StoreCityRequest $request){
        return response()->json($this->cityService->store($request->validated()), 201);
    }

    public function update(UpdateCityRequest $request, int $id){
        $city = $this->cityService->update($id, $request->validated());

        if(!$city)
            return response()->json(['message' => 'City not found'], 404);

        return response()->json($city);
    }

    public function destroy(int $id){
        $deleted = $this->cityService->delete($id);

        if(! $deleted)
            return response()->json(['message' => 'City not found'], 404);

        return response()->json(['message' => 'City deleted']);
    }
}

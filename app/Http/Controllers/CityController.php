<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UpdateCityRequest;
use App\Http\Requests\StoreCityRequest;
use App\Http\Resources\CityResource;
use App\Models\City;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\JsonResponse;


class CityController extends Controller
{
    public function index()
    {
        $companies = City::paginate(10);
        return new JsonResponse([
            'message' => 'success',
            'data' => CityResource::collection($companies),
        ], Response::HTTP_OK);
    }

    public function store(StoreCityRequest $request)
    {

        $validated = $request->validated();
        $company = City::create($validated);
        return new JsonResponse([
            'message' => 'success',
        ], Response::HTTP_OK);
    }

    //update
    public function update(UpdateCityRequest $request)
    {
        $validated = $request->validated();
        $company = City::find($validated['id']);
        $company->update($validated);
        return new JsonResponse([
            'message' => 'success',
        ], Response::HTTP_OK);
    }
    public function destroy(Request $request)
    {
        $company = City::find($request->id);
        $company->delete();
        return new JsonResponse([
            'message' => 'success',
        ], Response::HTTP_OK);
    }
}

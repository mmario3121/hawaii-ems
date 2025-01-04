<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UpdateHolidayRequest;
use App\Http\Requests\StoreHolidayRequest;
use App\Http\Resources\HolidayResource;
use App\Models\Holiday;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\JsonResponse;


class HolidayController extends Controller
{
    //
    public function index()
    {
        $holidays = Holiday::all();
        return new JsonResponse([
            'message' => 'success',
            'data' => HolidayResource::collection($holidays),
        ], Response::HTTP_OK);
    }

    public function store(StoreHolidayRequest $request)
    {

        $validated = $request->validated();
        $holiday = Holiday::create($validated);
        return new JsonResponse([
            'message' => 'success',
        ], Response::HTTP_OK);
    }

    //update
    public function update(UpdateHolidayRequest $request)
    {
        $validated = $request->validated();
        $holiday = Holiday::find($validated['id']);
        $holiday->update($validated);
        return new JsonResponse([
            'message' => 'success',
        ], Response::HTTP_OK);
    }
    public function destroy(Request $request)
    {
        $holiday = Holiday::find($request->id);
        $holiday->delete();
        return new JsonResponse([
            'message' => 'success',
        ], Response::HTTP_OK);
    }
}

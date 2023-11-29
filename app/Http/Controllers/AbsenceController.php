<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UpdateAbsenceRequest;
use App\Http\Requests\StoreAbsenceRequest;
use App\Http\Resources\AbsenceResource;
use App\Models\Absence;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\JsonResponse;


class AbsenceController extends Controller
{
    //
    public function index()
    {
        $companies = Absence::paginate(10);
        return new JsonResponse([
            'message' => 'success',
            'data' => AbsenceResource::collection($companies),
        ], Response::HTTP_OK);
    }

    public function store(StoreAbsenceRequest $request)
    {

        $validated = $request->validated();
        $absence = Absence::create($validated);
        return new JsonResponse([
            'message' => 'success',
        ], Response::HTTP_OK);
    }

    //update
    public function update(UpdateAbsenceRequest $request)
    {
        $validated = $request->validated();
        $absence = Absence::find($validated['id']);
        $absence->update($validated);
        return new JsonResponse([
            'message' => 'success',
        ], Response::HTTP_OK);
    }
    public function destroy(Absence $absence)
    {
        $absence->delete();
        return new JsonResponse([
            'message' => 'success',
        ], Response::HTTP_OK);
    }
}

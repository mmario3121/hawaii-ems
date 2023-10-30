<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Position;
use App\Http\Resources\PositionResource;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\JsonResponse;

class PositionController extends Controller
{
    //
    public function index()
    {
        $positions = Position::paginate(10);
        return new JsonResponse([
            'message' => 'success',
            'data' => PositionResource::collection($positions),
        ], Response::HTTP_OK);
    }
    public function store(Request $request) 
    {
        $position = Position::create($request->all());
        return new JsonResponse([
            'message' => 'success',
        ], Response::HTTP_OK);
    }
    
    public function update(Request $request)
    {
        $position = Position::find($request->id);
        $position->update($request->all());
        return new JsonResponse([
            'message' => 'success',
        ], Response::HTTP_OK);
    }
    public function destroy(Request $request)
    {
        $position = Position::find($request->id);
        $position->delete();
        return new JsonResponse([
            'message' => 'success',
        ], Response::HTTP_OK);
    }

}

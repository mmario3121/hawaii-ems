<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UpdateBranchRequest;
use App\Http\Requests\StoreBranchRequest;
use App\Http\Resources\BranchResource;
use App\Models\Branch;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\JsonResponse;


class BranchController extends Controller
{
    public function index()
    {
        $branches = Branch::all();
        return new JsonResponse([
            'message' => 'success',
            'data' => BranchResource::collection($branches),
        ], Response::HTTP_OK);
    }

    public function store(StoreBranchRequest $request)
    {

        $validated = $request->validated();
        $branch = Branch::create($validated);
        return new JsonResponse([
            'message' => 'success',
        ], Response::HTTP_OK);
    }

    //update
    public function update(UpdateBranchRequest $request)
    {
        $validated = $request->validated();
        $branch = Branch::find($validated['id']);
        $branch->update($validated);
        return new JsonResponse([
            'message' => 'success',
        ], Response::HTTP_OK);
    }
    public function destroy(Request $request)
    {
        $branch = Branch::find($request->id);
        $branch->delete();
        return new JsonResponse([
            'message' => 'success',
        ], Response::HTTP_OK);
    }
}

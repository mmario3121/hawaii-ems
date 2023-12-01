<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UpdateDepartmentRequest;
use App\Http\Requests\StoreDepartmentRequest;
use App\Http\Resources\DepartmentResource;
use App\Models\Department;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\JsonResponse;

class DepartmentController extends Controller
{
    public function store(StoreDepartmentRequest $request)
    {
        $validated = $request->validated();
        $department = Department::create($validated);
        return new JsonResponse([
            'message' => 'success',
        ], Response::HTTP_OK);
    }

    //update
    public function update(UpdateDepartmentRequest $request)
    {
        $validated = $request->validated();
        $department = Department::find($validated['id']);
        $department->update($validated);
        return new JsonResponse([
            'message' => 'success',
        ], Response::HTTP_OK);
    }

    //destroy
    public function destroy(Request $request)
    {
        $department = Department::find($request->id);
        $department->delete();
        return new JsonResponse([
            'message' => 'success',
        ], Response::HTTP_OK);
    }

    //index with resource and pagination
    public function index()
    {
        $departments = Department::with('owner', 'zams', 'groups')->get();
        return new JsonResponse([
            'message' => 'success',
            'data' => DepartmentResource::collection($departments),
        ], Response::HTTP_OK);
    }

    //getDepartmentById
    public function getDepartmentById(Request $request)
    {
        $department = Department::with('owner', 'zams', 'groups')->find($request->id);
        return new JsonResponse([
            'message' => 'success',
            'data' => new DepartmentResource($department),
        ], Response::HTTP_OK);
    }
}

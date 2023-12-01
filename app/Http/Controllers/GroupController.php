<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreGroupRequest;
use App\Http\Requests\UpdateGroupRequest;
use App\Http\Resources\GroupResource;
use App\Models\Group;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\JsonResponse;

class GroupController extends Controller
{
    //
    public function index()
    {
        $groups = Group::all();
        return new JsonResponse([
            'message' => 'success',
            'data' => GroupResource::collection($groups),
        ], Response::HTTP_OK);
    }    

    public function store(StoreGroupRequest $request)
    {
        $validated = $request->validated();
        $group = Group::create($validated);
        return new JsonResponse([
            'message' => 'success',
        ], Response::HTTP_OK);
    }

    public function update(UpdateGroupRequest $request)
    {
        $group = Group::find($request->id);
        $group->update($request->all());
        return new JsonResponse([
            'message' => 'success',
        ], Response::HTTP_OK);
    }

    public function destroy(Request $request)
    {
        $group = Group::find($request->id);
        $group->delete();
        return new JsonResponse([
            'message' => 'success',
        ], Response::HTTP_OK);
    }

    //getGroupByDepartmentId
    public function getGroupByDepartmentId(Request $request)
    {
        $groups = Group::where('department_id', $request->id)->get();
        return new JsonResponse([
            'message' => 'success',
            'data' => GroupResource::collection($groups),
        ], Response::HTTP_OK);
    }

}

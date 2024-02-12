<?php

namespace App\Http\Controllers;

use App\Http\Resources\EmployeeIdResource;
use Illuminate\Http\Request;
use App\Http\Requests\StoreGroupRequest;
use App\Http\Requests\UpdateGroupRequest;
use App\Http\Resources\GroupResource;
use App\Models\Group;
use App\Models\Employee;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\JsonResponse;

class GroupController extends Controller
{
    //
    public function index()
    {
        $groups = Group::all();
        $groups->load('departments');
        return new JsonResponse([
            'message' => 'success',
            'data' => GroupResource::collection($groups),
        ], Response::HTTP_OK);
    }    

    public function store(Request $request)
    {
        $group = new Group;
        $group->name = $request->name;
        $group->save();
        $departmentIds = explode(',', $request->departments);
        $group->departments()->sync($departmentIds);
        return new JsonResponse([
            'message' => 'success',
        ], Response::HTTP_OK);
    }

    public function update(Request $request)
    {
        $group = Group::find($request->id);
        $group->name = $request->name;
        $group->save();
    
        $departmentIds = explode(',', $request->departments);

        $group->departments()->sync($departmentIds);
        return new JsonResponse([
            'message' => 'success',
        ], Response::HTTP_OK);
    }

    public function destroy(Request $request)
    {
        $group = Group::find($request->id);
        $employees = Employee::where('group_id', $request->id)->get();
        // dd($request->id);
        foreach($employees as $employee) {
            $employee->group_id = null;
            $employee->save();
        }
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

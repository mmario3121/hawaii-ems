<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UpdateDepartmentRequest;
use App\Http\Requests\StoreDepartmentRequest;
use App\Http\Resources\DepartmentResource;
use App\Http\Resources\DepartmentListResource;
use App\Models\Department;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\JsonResponse;
use App\Models\Company;

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
        $department->groups()->delete();
        $department->employees->each(function($employee) {
            $employee->workdays()->delete();
        });
        $department->employees()->delete();
        $department->delete();
        return new JsonResponse([
            'message' => 'success',
        ], Response::HTTP_OK);
    }

    //index with resource and pagination
    public function index()
    {

        //check user role
        $user = auth()->user();
        $role = $user->roles->pluck('name');
        if($role->contains('manager')) {
            $departments = Department::with('owner', 'zams', 'groups')->where('owner_id', $user->id)->get();
            return new JsonResponse([
                'message' => 'success',
                'data' => DepartmentResource::collection($departments),
            ], Response::HTTP_OK);
        }elseif($role->contains('treasurer')) {
            $departments = Department::with('owner', 'zams', 'groups')->whereIn('id', [52, 54, 56, 55])->get();
            return new JsonResponse([
                'message' => 'success',
                'data' => DepartmentResource::collection($departments),
            ], Response::HTTP_OK);
        }
        if($role->contains('admin')) {
            $departments = Department::with('owner', 'zams', 'groups')->get();
            return new JsonResponse([
                'message' => 'success',
                'data' => DepartmentResource::collection($departments),
            ], Response::HTTP_OK);
        }
        $companies = Company::where('branch_id', $user->branch_id)->pluck('id')->toArray();
        $departments = Department::with('owner', 'zams', 'groups')
            ->whereIn('company_id', $companies)
            ->get();

    }
    public function list()
    {
        $user = auth()->user();
        $companies = Company::where('branch_id', $user->branch_id)->pluck('id')->toArray();
        if($user->hasRole('admin')){
            $departments = Department::with('owner', 'zams', 'groups')->get();
        }else{
            $departments = Department::with('owner', 'zams', 'groups')
                ->whereIn('company_id', $companies)
                ->get();
        }
        return new JsonResponse([
            'message' => 'success',
            'data' => DepartmentListResource::collection($departments),
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

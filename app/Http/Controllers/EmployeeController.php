<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Resources\EmployeeResource;
use App\Models\Employee;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\UpdateEmployeeRequest;
use Illuminate\Http\JsonResponse;

class EmployeeController extends Controller
{
    //store
    public function store(StoreEmployeeRequest $request)
    {
        $employee = Employee::create($request->all());
        return new JsonResponse([
            'message' => 'success',
        ], Response::HTTP_OK);
    }

    //update
    public function update(UpdateEmployeeRequest $request)
    {
        $employee = Employee::find($request->id);
        $employee->update($request->all());
        return new JsonResponse([
            'message' => 'success',
        ], Response::HTTP_OK);
    }

    //destroy
    public function destroy(Request $request)
    {
        $employee = Employee::find($request->id);

        //delete workdays
        $employee->workdays()->delete();
        $employee->delete();
        return new JsonResponse([
            'message' => 'success',
        ], Response::HTTP_OK);
    }

    //index with resource and pagination
    public function index()
    {
        $employees = Employee::paginate(10);
        return new JsonResponse([
            'message' => 'success',
            'data' => EmployeeResource::collection($employees),
        ], Response::HTTP_OK);
    }

    //show with resource
    public function show(Request $request)
    {
        $employee = Employee::find($request->id);
        return new JsonResponse([
            'message' => 'success',
            'data' => new EmployeeResource($employee),
        ], Response::HTTP_OK);
    }

    //getByDepartment
    public function getByDepartment(Request $request)
    {
        $employees = Employee::where('department_id', $request->department_id)->paginate(10);
        return new JsonResponse([
            'message' => 'success',
            'data' => EmployeeResource::collection($employees),
        ], Response::HTTP_OK);
    }
    //getByCompany
    public function getByCompany(Request $request)
    {
        $employees = Employee::where('company_id', $request->company_id)->paginate(10);
        return new JsonResponse([
            'message' => 'success',
            'data' => EmployeeResource::collection($employees),
        ], Response::HTTP_OK);
    }   

    //getEmployeeById
    public function getEmployeeById(Request $request)
    {
        $employee = Employee::findorFail($request->id);
        return new JsonResponse([
            'message' => 'success',
            'data' => new EmployeeResource($employee),
        ], Response::HTTP_OK);
    }
}

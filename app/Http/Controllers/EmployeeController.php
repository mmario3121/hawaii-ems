<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Resources\EmployeeResource;
use App\Http\Resources\EmployeeIdResource;
use App\Models\Employee;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\UpdateEmployeeRequest;
use Illuminate\Http\JsonResponse;
use App\Models\Shift;
use App\Models\Workday;
use Carbon\Carbon;

class EmployeeController extends Controller
{
    //store
    public function store(StoreEmployeeRequest $request)
    {
        $employee = Employee::create($request->all());
        if($request->shift != null){
            $this->generateWorkdays($employee->id, $request->shift);
        }
        return new JsonResponse([
            'message' => 'success',
        ], Response::HTTP_OK);
    }

    //update
    public function update(UpdateEmployeeRequest $request)
    {
        $employee = Employee::find($request->id);
        $employee->update($request->all());
        if($request->shift != null){
            $this->generateWorkdays($employee->id, $request->shift);
        }
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
        $employees = Employee::paginate(20);
        return EmployeeResource::collection($employees);
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
    public function getByDepartment($id)
    {
        if(isset($id) == false){
            $employees = Employee::paginate(10);
            return EmployeeResource::collection($employees);
        }else{
            $employees = Employee::where('department_id', $id)->paginate(10);
        }
        return EmployeeResource::collection($employees);
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

    public function getEmployeeId(Request $request)
    {
        $employee = Employee::findorFail($request->id);
        return new JsonResponse([
            'message' => 'success',
            'data' => new EmployeeIdResource($employee),
        ], Response::HTTP_OK);
    }


    public function generateWorkdays($employee_id, $shift_id)
    {
        // Assuming you have the necessary relationship between Employee and Shift in your models
        $employee = Employee::find($employee_id);
        
        $shift = Shift::find($shift_id);
        // dd($shift);
        
        $employee->shift = $shift->id;
        $employee->save();
        // Assuming $shift has 'work_days' and 'vacation_days' properties
        $workDays = $shift->work_days;
        $vacationDays = $shift->vacation_days;

        $startDate = $shift->start_date; // Use the start_date from the assigned shift
        // $endDate = Carbon::parse($startDate)->endOfMonth();
        //end date is end of current month
        $endDate = Carbon::now()->endOfMonth();
        for ($date = Carbon::parse($startDate); $date->lte($endDate);) {
            // Check if a workday exists for the current date and employee
            

                // If it doesn't exist, create a new one
                for ($i = 0; $i < $workDays; $i++) {
                    $existingWorkday = Workday::where('date', $date)
                    ->where('employee_id', $employee->id)
                    ->first();
                    if ($existingWorkday) {
                        $existingWorkday->update([
                            'isWorkday' => 1,
                        ]);
                    }else{
                        Workday::create([
                            'date' => $date,
                            'employee_id' => $employee->id,
                            'isWorkday' => 1,
                        ]);
                    }
                    $date->addDay();
                }

                for ($i = 0; $i < $vacationDays; $i++) {
                    $existingWorkday = Workday::where('date', $date)
                    ->where('employee_id', $employee->id)
                    ->first();
                    if ($existingWorkday) {
                        $existingWorkday->update([
                            'isWorkday' => 0,
                        ]);
                    }else{
                        Workday::create([
                            'date' => $date,
                            'employee_id' => $employee->id,
                            'isWorkday' => 0,
                        ]);
                    }
                    $date->addDay();
                }
        }

        return response()->json(['message' => 'Workdays generated successfully']);
    }

    public function getEmployees()
    {
        $employees = Employee::all();
        return $employees;
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\Employee;

class DepartmentEmployeeController extends Controller
{
    public function store(Request $request)
    {
        $department = Department::find($request->department_id);
        $employee = Employee::find($request->employee_id);
        $department->zams()->attach($employee);
        return response()->json(['message' => 'success']);
    }

    public function destroy(Request $request)
    {
        $department = Department::find($request->department_id);
        $employee = Employee::find($request->employee_id);
        $department->zams()->detach($employee);
        return response()->json(['message' => 'success']);
    }
}

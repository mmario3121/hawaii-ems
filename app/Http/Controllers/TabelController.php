<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Department;
use App\Http\Resources\Tabel\DepartmentResource;

class TabelController extends Controller
{
    //
    public function index(Request $request)
    {
        $departments = Department::with('employees.workdays')->find($request->department_id);
        $data = new DepartmentResource($departments);

        return response()->json(['data' => $data]);
    }
}
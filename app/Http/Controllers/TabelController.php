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
        $department = Department::with(['groups.employees.workdays', 'groups'])->find($request->department_id);
        
        $data = new DepartmentResource($department);

        return response()->json(['data' => $data]);
    }
}
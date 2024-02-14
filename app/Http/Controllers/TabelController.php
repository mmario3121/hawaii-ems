<?php

namespace App\Http\Controllers;

use App\Models\Holiday;
use Illuminate\Http\Request;
use App\Models\Department;
use App\Http\Resources\Tabel\DepartmentResource;
use App\Http\Resources\Norm\DepartmentResource as DepartmentNormResource;

class TabelController extends Controller
{
    //
    public function index(Request $request)
    {
        $yearMonth = $request->input('year_month'); // предполагается, что 'year_month' - это ключ в запросе
        [$year, $month] = explode('-', $yearMonth);

    $department = Department::with([
        'groups.employees' => function ($query) use ($request) {
            $query->where('department_id', $request->department_id);
        },
        'groups.employees.workdays' => function ($query) use ($year, $month) {
            $query->whereYear('date', $year)
                  ->whereMonth('date', $month);
        }, 
        'groups', 
        'owner',
    ])->find($request->department_id);
    
    $data = new DepartmentResource($department);
    return response()->json(['data' => $data]);
    }


    public function norm(Request $request)
    {
        $yearMonth = $request->input('year_month'); // предполагается, что 'year_month' - это ключ в запросе
        [$year, $month] = explode('-', $yearMonth);

    $department = Department::with([
        'groups.employees' => function ($query) use ($request) {
            $query->where('department_id', $request->department_id);
        },
        'groups.employees.workdays' => function ($query) use ($year, $month) {
            $query->whereYear('date', $year)
                  ->whereMonth('date', $month);
        }, 
        'groups', 
        'owner',
    ])->find($request->department_id);
    
    $data = new DepartmentNormResource($department);
    return response()->json(['data' => $data]);
    }
}
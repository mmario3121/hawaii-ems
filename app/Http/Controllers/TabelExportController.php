<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TabelExport;


class TabelExportController extends Controller
{
    public function export(Request $request) 
    {
        $departmentId = $request->input('department_id');
        $year = $request->input('year');
        $month = $request->input('month');
        return Excel::download(new TabelExport($departmentId, $year, $month), 'tabel.xlsx');

    }
}

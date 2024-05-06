<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TabelExport;
use App\Exports\PremExportView;
use App\Exports\TabelExportView;
use App\Exports\NormExportView;


class TabelExportController extends Controller
{
    public function export(Request $request) 
    {
        $departmentId = $request->input('department_id');
        $year = $request->input('year');
        $month = $request->input('month');
        return Excel::download(new TabelExport($departmentId, $year, $month), 'tabel.xlsx');
    }

    public function export1(Request $request) 
    {
        $departmentId = $request->input('department_id');
        $year = $request->input('year');
        $month = $request->input('month');
        $ids = $request->input('ids');
        return Excel::download(new TabelExportView($departmentId, $year, $month, $ids), 'tabel.xlsx');
    }

    public function prem(Request $request) 
    {
        $departmentId = $request->input('department_id');
        $year = $request->input('year');
        $month = $request->input('month');
        $ids = $request->input('ids');
        return Excel::download(new PremExportView($departmentId, $year, $month, $ids), 'tabel.xlsx');
    }

    public function norm(Request $request) 
    {
        $departmentId = $request->input('department_id');
        $year = $request->input('year');
        $month = $request->input('month');
        $ids = $request->input('ids');
        return Excel::download(new NormExportView($departmentId, $year, $month, $ids), 'tabel.xlsx');
    }
}

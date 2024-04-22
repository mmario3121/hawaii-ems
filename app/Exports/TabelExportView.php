<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use App\Models\Employee;
use Carbon\Carbon;

class TabelExportView implements FromView
{
    private $departmentId;
    private $year;
    private $month;

    public function __construct(int $departmentId, int $year, int $month)
    {
        $this->departmentId = $departmentId;
        $this->year = $year;
        $this->month = $month;
    }

    public function view(): View
    {
        $startDate = Carbon::createMidnightDate($this->year, $this->month, 1, 'Asia/Almaty');
        $endDate = $startDate->copy()->endOfMonth();

        $employees = Employee::where('department_id', $this->departmentId)
            ->with(['workdays' => function ($query) use ($startDate, $endDate) {
                $query->whereBetween('date', [$startDate, $endDate])
                    ->with('absence')
                    ->select('id', 'employee_id', 'date', 'workhours', 'absence_id');
            }])->get();
        
        $dates = collect();
        for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
            $dates->push($date->copy());
         }

        $year_month = $startDate->format('Y-m');

        return view('employees', [
            'employees' => $employees,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'dates' => $dates,
            'year_month' => $year_month
        ]);
    }
}
?>
<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use App\Models\Employee;
use Carbon\Carbon;

class NormExportView implements FromView
{
    private $departmentId;
    private $year;
    private $month;

    private $ids;

    public function __construct(int $departmentId, int $year, int $month, $ids)
    {
        $this->departmentId = $departmentId;
        $this->year = $year;
        $this->month = $month;
        $this->ids = $ids;
    }

    public function view(): View
    {
        $startDate = Carbon::createMidnightDate($this->year, $this->month, 1, 'Asia/Almaty');
        $endDate = $startDate->copy()->endOfMonth();

        if($this->ids){
            $employees = Employee::whereIn('id', explode(',', $this->ids))
            ->with(['workdays' => function ($query) use ($startDate, $endDate) {
                $query->whereBetween('date', [$startDate, $endDate])
                    ->with('absence')
                    ->select('id', 'employee_id', 'date', 'workhours', 'absence_id', 'isWorkday');
            }])->get();
        }
        else{
            $employees = Employee::where('department_id', $this->departmentId)
                ->with(['workdays' => function ($query) use ($startDate, $endDate) {
                    $query->whereBetween('date', [$startDate, $endDate])
                        ->with('absence')
                        ->select('id', 'employee_id', 'date', 'workhours', 'absence_id', 'isWorkday');
                }])->get();
        
            }
        $dates = collect();
        $date = $startDate->copy();
        for (; $date->lte($endDate); $date->addDay()) {
            $dates->push($date->copy());
        }

        $year_month = $startDate->format('Y-m');
        return view('norm', [
            'employees' => $employees,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'dates' => $dates,
            'year_month' => $year_month
        ]);
    }
}
?>
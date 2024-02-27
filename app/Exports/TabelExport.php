<?php

namespace App\Exports;

use App\Models\Employee;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;
use Carbon\Carbon;

class TabelExport implements FromCollection, WithHeadings
{
    protected $departmentId;
    protected $year;
    protected $month;

    public function __construct($departmentId, $year, $month)
    {
        $this->departmentId = $departmentId;
        $this->year = $year;
        $this->month = $month;
    }

    public function collection()
    {
        $startDate = Carbon::createFromDate($this->year, $this->month, 1, 'Asia/Almaty');
        $endDate = $startDate->copy()->endOfMonth();

        return Employee::where('department_id', $this->departmentId)
            ->with(['workdays' => function ($query) use ($startDate, $endDate) {
                $query->whereBetween('date', [$startDate, $endDate])
                    ->select('id', 'employee_id', 'date', 'workhours', 'isWorkday');
            }])
            ->get()
            ->map(function ($employee) use ($startDate, $endDate) {
                $row = [
                    'Employee ID' => $employee->id,
                    'Name' => $employee->name,
                ];

                foreach ($employee->workdays as $workday) {
                    $row[$workday->date] = $workday->workhours;
                }

                return $row;
            });
    }

    public function headings(): array
    {
        $headings = [
            'Employee ID',
            'Name',
        ];

        $startDate = Carbon::createFromDate($this->year, $this->month, 1);
        $endDate = $startDate->copy()->endOfMonth();

        while ($startDate->lte($endDate)) {
            $headings[] = $startDate->toDateString();
            $startDate->addDay();
        }

        return $headings;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet;

                // Define range and formatting rules
                $range = 'A2:' . $sheet->getHighestColumn() . $sheet->getHighestRow();
                $style = [
                    'font' => ['color' => ['rgb' => '008000']],
                    // Green color for workdays
                ];

                // Apply conditional formatting
                $sheet->getStyle($range)->getFont()->getColor()->setARGB('FF808080');
                // Grey color for non-workdays
            },
        ];
    }
}

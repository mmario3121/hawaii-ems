<?php

namespace App\Exports;

use App\Models\Employee;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithConditionalFormatting;
use Carbon\Carbon;

class TabelExport implements FromCollection, WithHeadings, WithConditionalFormatting
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
                $style = ['font' => ['color' => ($workday->isWorkday == 1) ? ['argb' => 'FF008000'] : ['argb' => 'FF808080']]];
                $row[$workday->date] = [
                    'value' => $workday->workhours,
                    'style' => $style,
                ];
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

    public function conditionalFormats(): array
    {
        return [
            'A2:ZZ1000' => [
                [
                    'rule' => '==1',
                    'stopIfTrue' => true,
                    'style' => [
                        'font' => [
                            'color' => ['argb' => 'FF008000'],
                        ],
                    ],
                ],
                [
                    'rule' => '==0',
                    'stopIfTrue' => true,
                    'style' => [
                        'font' => [
                            'color' => ['argb' => 'FF808080'],
                        ],
                    ],
                ],
            ],
        ];
    }
}

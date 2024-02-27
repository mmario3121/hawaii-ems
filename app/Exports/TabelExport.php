<?php

namespace App\Exports;

use App\Models\Employee;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;
use Illuminate\Support\Facades\DB;
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

        $employees = Employee::where('department_id', $this->departmentId)
            ->with(['workdays' => function ($query) use ($startDate, $endDate) {
                $query->whereBetween('date', [$startDate, $endDate])
                    ->select('id', 'employee_id', 'date', 'workhours', 'isWorkday');
            }])
            ->get();

        $data = [];

        foreach ($employees as $employee) {
            $row = [
                'Employee ID' => $employee->id,
                'Name' => $employee->name,
            ];

            foreach ($employee->workdays as $workday) {
                $value = $workday->workhours;
                $style = ['font' => ['color' => ($workday->isWorkday == 1) ? ['argb' => 'FF008000'] : ['argb' => 'FF808080']]];
                $data[] = array_merge($row, [$workday->date => $value], [$workday->date . '_style' => $style]);
            }
        }

        return collect($data);
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

                foreach ($sheet->getRowIterator() as $row) {
                    foreach ($row->getCellIterator() as $cell) {
                        $cellValue = $cell->getValue();
                        $cellColumn = $cell->getColumn();
                        $cellStyle = $sheet->getStyle($cellColumn . $cell->getRow());
                        
                        // Check if style data is available for this cell
                        if (isset($this->styleData[$cellValue . '_style'])) {
                            $style = $this->styleData[$cellValue . '_style'];
                            $cellStyle->applyFromArray($style);
                        }
                    }
                }
            },
        ];
    }
}

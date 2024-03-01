<?php
namespace App\Exports;

use App\Models\Employee; // Your Employee model
use App\Models\Workday;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Carbon\Carbon;

class TabelExport implements FromCollection, WithHeadings
{
    protected $departmentId;
    protected $year;
    protected $month;

    /**
     * Accept department, year, and month for filtering.
     */
    public function __construct($departmentId, $year, $month)
    {
        $this->departmentId = $departmentId;
        $this->year = $year;
        $this->month = $month;
    }
    public function collection()
    {
        $startDate = Carbon::createMidnightDate($this->year, $this->month, 1, 'Asia/Almaty');
        $endDate = $startDate->copy()->endOfMonth();

        return Employee::where('department_id', $this->departmentId)
            ->with(['workdays' => function ($query) use ($startDate, $endDate) {
                $query->whereBetween('date', [$startDate, $endDate])
                    ->with('absence')
                    ->select('id', 'employee_id', 'date', 'workhours'); // select only required fields
            }])->get()->map(function ($employee) {
                $row = [
                    'Employee ID' => $employee->id,
                    'Name' => $employee->name,
                    // Include other employee attributes here
                ];

                // Append each workday's data
                foreach ($employee->workdays as $workday) {
                    if($workday->absense_id != null){
                        $row[$workday->date] = $workday->absense->title;
                        continue;
                    }
                    //find if there is a holiday if date is between start and end date of holiday
                    $holiday = Holiday::where('start_date', '<=', $workday->date)
                        ->where('end_date', '>=', $workday->date)
                        ->first();
                    if ($holiday) {
                        $row[$workday->date] = 'П';
                        continue;
                    }
                    $row[$workday->date] = $workday->workhours;
                }
                $year_month = $this->year . '-' . $this->month;
                $row['Days'] = $employee->workdays_last_month($year_month);
                $row['Hours'] = $employee->workhours($year_month);
                $row['Norm'] = $employee->norm($year_month);
                $row['+/-'] = strval(($employee->norm($year_month) ?? 0) - ($employee->workhours($year_month) ?? 0));
                $row['Bin'] = $employee->company->bin;
                $row['Company'] = $employee->company->title;
                return $row;
            });
    }

    /**
     * Define column headings for the Excel file.
     * This should match the structure of the collection.
     */
    public function headings(): array
    {
        // Start with static employee headings
        $headings = [
            'ID',
            'ФИО',
            // Add other static employee attribute headings
        ];

        // Generate date range for the specified month and year
        $startDate = Carbon::createMidnightDate($this->year, $this->month, 1, 'Asia/Almaty');
        $endDate = $startDate->copy()->endOfMonth();

        while ($startDate->lte($endDate)) {
            $headings[] = $startDate->toDateString(); // Add each date of the month as a heading
            $startDate->addDay();
        }
        $headings[] = 'Дни';
        $headings[] = 'Часы';
        $headings[] = 'Норма';
        $headings[] = '+/-';
        $headings[] = 'БИН';
        $headings[] = 'Компания';

        return $headings;
    }
}
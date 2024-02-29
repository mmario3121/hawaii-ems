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
                    ->select('id', 'employee_id', 'date', 'workhours'); // select only required fields
            }])->get()->map(function ($employee) {
                $row = [
                    'Employee ID' => $employee->id,
                    'Name' => $employee->name,
                    // Include other employee attributes here
                ];

                // Append each workday's data
                foreach ($employee->workdays as $workday) {
                    $row[$workday->date] = $workday->workhours;
                }
                $year_month = $this->year . '-' . $this->month;
                $row['Days'] = $employee->workdays_last_month($year_month);
                $row['Hours'] = $employee->workhours($year_month);
                $row['Norm'] = $employee->norm($year_month);
                $row['Bin'] = $employee->company->bin;
                $row['+/-'] = $employee->norm($year_month) - $employee->workhours($year_month);
                $row['Company'] = $employee->company->name;
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
            'Employee ID',
            'Name',
            // Add other static employee attribute headings
        ];

        // Generate date range for the specified month and year
        $startDate = Carbon::createMidnightDate($this->year, $this->month, 1, 'Asia/Almaty');
        $endDate = $startDate->copy()->endOfMonth();

        while ($startDate->lte($endDate)) {
            $headings[] = $startDate->toDateString(); // Add each date of the month as a heading
            $startDate->addDay();
        }

        return $headings;
    }
}
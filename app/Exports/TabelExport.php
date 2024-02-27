<?php
namespace App\Exports;

use App\Models\Employee; // Your Employee model
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
        // Convert year and month to a date range
        $startDate = Carbon::createFromDate($this->year, $this->month, 1, 'Asia/Almaty');
        $endDate = $startDate->copy()->endOfMonth();

        // Adjust the query as per your actual database structure and needs
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
                    $row[$workday->date->modify('+1 day')->toDateString()] = $workday->workhours;
                }

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
        $startDate = Carbon::createFromDate($this->year, $this->month, 1, 'Asia/Almaty');
        $endDate = $startDate->copy()->endOfMonth();

        while ($startDate->lte($endDate)) {
            $headings[] = $startDate->toDateString(); // Add each date of the month as a heading
            $startDate->addDay();
        }

        return $headings;
    }
}
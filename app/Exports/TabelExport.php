<?php
namespace App\Exports;

use App\Models\Employee; // Your Employee model
use App\Models\Workday;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Carbon\Carbon;
use App\Models\Holiday;

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
                    ->select('id', 'employee_id', 'date', 'workhours', 'absence_id'); // select only required fields
            }])->get()->map(function ($employee) {
                $row = [
                    'Employee ID' => $employee->id,
                    'Name' => $employee->name,
                    // Include other employee attributes here
                ];

                // Append each workday's data
                //days in month
                $startDate = Carbon::createMidnightDate($this->year, $this->month, 1, 'Asia/Almaty');
                $days = $startDate->daysInMonth;
                $count = 0;
                foreach ($employee->workdays as $workday) {
                    $count++;
                    $day = Carbon::parse($workday->date)->format('d');
                    $row[$day] = $workday->workhours;
                    if($workday->absence_id != null){
                        $row[$day] = $workday->absence->type;
                        continue;
                    }
                    //find if there is a holiday if date is between start and end date of holiday
                    $holiday = Holiday::where('start_date', '<=', $workday->date)
                        ->where('end_date', '>=', $workday->date)
                        ->first();
                    if($employee->getShift() != null){
                        if ($holiday && $employee->getShift()->work_days >= 5) {
                            $row[$day] = 'П';
                            continue;
                        }
                    }
                    if($row[$day] == null){
                        $row[$day] = " ";
                    }
                }
                if($count < $days){
                    for($i = $count; $i < $days + 3; $i++){
                        $row[$i+1] = "a";
                    }
                }
                $year_month = $this->year . '-' . $this->month;
                $row['Days'] = $employee->workdays_last_month($year_month)?? "0";
                $row['Hours'] =$employee->workhours($year_month)?? "0";
                $row['Norm'] = $employee->norm($year_month)?? "0";
                $row['+/-'] = strval(($employee->workhours($year_month) ?? 0) - ($employee->norm($year_month) ?? 0));
                $row['Bin'] = " ".strval($employee->company->bin);
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
            //display only day number
            $headings[] = $startDate->format('d');
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
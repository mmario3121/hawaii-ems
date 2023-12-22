<?php

namespace App\Console\Commands;

use App\Http\Controllers\ShiftController;
use Illuminate\Console\Command;
use App\Http\Controllers\EmployeeController;

class GenerateWorkdaysForAllEmployees extends Command
{
    protected $signature = 'workdays:generate';

    protected $description = 'Generate workdays for all employees';

    public function handle()
    {
        $controller = new EmployeeController();

        // Предполагается, что у вас есть метод, который возвращает всех сотрудников
        $employees = $controller->getEmployees();

        foreach ($employees as $employee) {
            // dd($employee->shift);
            $controller->generateWorkdays($employee->id, 
            $employee->getShiftId()
            );
        }

        $this->info('Workdays generated for all employees');
    }
}

?>
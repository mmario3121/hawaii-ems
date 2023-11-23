<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Workday;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Shift;
use App\Http\Resources\ShiftResource;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\JsonResponse;

class ShiftController extends Controller
{
    //
    public function index()
    {
        $shifts = Shift::paginate(10);
        return new JsonResponse([
            'message' => 'success',
            'data' => ShiftResource::collection($shifts),
        ], Response::HTTP_OK);
    }
    public function store(Request $request) 
    {
        $shift = Shift::create($request->all());
        return new JsonResponse([
            'message' => 'success',
        ], Response::HTTP_OK);
    }
    
    public function update(Request $request)
    {
        $shift = Shift::find($request->id);
        $shift->update($request->all());
        return new JsonResponse([
            'message' => 'success',
        ], Response::HTTP_OK);
    }
    public function destroy(Request $request)
    {
        $shift = Shift::find($request->id);
        $shift->delete();
        return new JsonResponse([
            'message' => 'success',
        ], Response::HTTP_OK);
    }


    public function generateWorkdays(Request $request)
    {
        // Assuming you have the necessary relationship between Employee and Shift in your models
        $employee = Employee::find($request->employee_id);
        
        $shift = Shift::find($request->shift_id);
        
        $employee->shift = $shift->id;
        $employee->save();
        // Assuming $shift has 'work_days' and 'vacation_days' properties
        $workDays = $shift->work_days;
        $vacationDays = $shift->vacation_days;

        $startDate = $shift->start_date; // Use the start_date from the assigned shift
        $endDate = Carbon::parse($startDate)->endOfMonth();

        for ($date = Carbon::parse($startDate); $date->lte($endDate);) {
            // Check if a workday exists for the current date and employee
            $existingWorkday = Workday::where('date', $date)
                ->where('employee_id', $employee->id)
                ->first();

            if ($existingWorkday) {
                // If it exists, update it
                $existingWorkday->update([
                    'isWorkday' => 1, // Update other fields as needed
                ]);
                $date->addDay();
            } else {
                // If it doesn't exist, create a new one
                for ($i = 0; $i < $workDays; $i++) {
                    Workday::create([
                        'date' => $date,
                        'employee_id' => $employee->id,
                        'isWorkday' => 1,
                    ]);
                    $date->addDay();
                }

                for ($i = 0; $i < $vacationDays; $i++) {
                    Workday::create([
                        'date' => $date,
                        'employee_id' => $employee->id,
                        'isWorkday' => 0,
                    ]);
                    $date->addDay();
                }
            }
        }

        return response()->json(['message' => 'Workdays generated successfully']);
    }
}

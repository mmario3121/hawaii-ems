<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Employee extends Model
{
    use HasFactory;

    //fillable

    protected $fillable = [
        'name',
        'birthday',
        'phone',
        'position_id',
        'iin',
        'email',
        'company_id',
        'shift',
        'department_id',
        'group_id',
        'salary_net',
        'salary_gross',
        'number',
        'city_id',
        'address',
    ];
    
    //relations
    protected $casts = [
        'birthday' => 'date',
    ];
    //position
    public function position()
    {
        return $this->belongsTo(Position::class, 'position_id');
    }
    //company
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
    //department
    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function workdays()
    {
        return $this->hasMany(Workday::class)->orderBy('date');
    }

    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id');
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    public function workhours_this()
    {
       //calculate workhours from workdays last month
        $workdays = $this->workdays()->whereBetween('date', [
            now()->startOfMonth(),
            now()->endOfMonth()
            ])->get();
        $workhours = 0;
        foreach ($workdays as $workday) {
            $workhours += $workday->workhours;
        }
        return $workhours;
    }

    public function workhours($year_month)
    {
        $year_month = explode('-', $year_month);
        $year = $year_month[0];
        $month = $year_month[1];
       //calculate workhours from workdays last month
       $startOfMonth = Carbon::create($year, $month, 1)->startOfDay();
        $endOfMonth = Carbon::create($year, $month, 1)->endOfMonth()->endOfDay();

        $workdays = $this->workdays()->whereBetween('date', [$startOfMonth, $endOfMonth])->get();
        $workhours = 0;
        foreach ($workdays as $workday) {
            $workhours += $workday->workhours;
        }
        return $workhours;
    }

    public function workdays_last_month($year_month)
    {
        $year_month = explode('-', $year_month);
        $year = $year_month[0];
        $month = $year_month[1];
        return $this->workdays()->whereBetween('date', [
            now()->startOfMonth()->setYear($year)->setMonth($month),
            now()->endOfMonth()->setYear($year)->setMonth($month)
            ])->where('workhours', '>', '0')->count();
    }
    public function workdays_this_month($year_month)
    {

        return $this->workdays()->whereBetween('date', [
            now()->startOfMonth(),
            now()->endOfMonth()
            ])->where('workhours', '>', '0')->count();
    }

    public function norm($year_month)
    {
        if($this->getShift() == null) return 0;
        $year_month = explode('-', $year_month);
        $year = $year_month[0];
        $month = $year_month[1];

        $startOfMonth = Carbon::create($year, $month, 1)->startOfDay();
        $endOfMonth = Carbon::create($year, $month, 1)->endOfMonth()->endOfDay();

        $workdays = $this->workdays()
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->where('isWorkday', '1');

        // Exclude holidays

        if($this->getShift()->work_days == 5){
            $holidays = Holiday::whereBetween('start_date', [$startOfMonth, $endOfMonth])
                ->orWhereBetween('end_date', [$startOfMonth, $endOfMonth])
                ->get();

            foreach ($holidays as $holiday) {
                $workdays->whereNotBetween('date', [$holiday->start_date, $holiday->end_date]);
            }
        }

        return $workdays->count() * $this->getShift()->shift_hours();
    }

    public function norm_worked($year_month)
    {
        $year_month = explode('-', $year_month);
        $year = $year_month[0];
        $month = $year_month[1];
        return $this->workdays()->whereBetween('date', [
            now()->startOfMonth()->setYear($year)->setMonth($month),
            now()->endOfMonth()->setYear($year)->setMonth($month)
            ])->where('isWorkday', '1')->
            sum('workhours');
    }

    //norm_days
    public function norm_days($year_month)
    {
        $year_month = explode('-', $year_month);
        $year = $year_month[0];
        $month = $year_month[1];

        $startOfMonth = Carbon::create($year, $month, 1)->startOfDay();
        $endOfMonth = Carbon::create($year, $month, 1)->endOfMonth()->endOfDay();

        // dd($startOfMonth, $endOfMonth);

        return $this->workdays()->whereBetween('date', [$startOfMonth, $endOfMonth])
                                ->where('isWorkday', '1')->count();
    }

    public function hourly_rate($year_month)
    {
        //calculate hourly rate, round to 2 decimals
        return number_format($this->salary_gross / $this->norm($year_month), 2, '.', '');
    }

    public function overtime_salary($year_month)
    {
        return $this->overtime($year_month) * $this->hourly_rate($year_month);
    }

    public function getShiftId()
    {
        $shift = Shift::find($this->shift);
        if($shift) return $shift->id;

    }

    //shift
    public function getShiftName()
    {
        $shift = Shift::find($this->shift);
        if($shift) return $shift->name;

    }

    public function getShift(){
        $shift = Shift::find($this->shift);
        if($shift) return $shift;
    }
}

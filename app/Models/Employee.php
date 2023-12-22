<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    public function workhours()
    {
       //calculate workhours from workdays last month
        $workdays = $this->workdays()->whereBetween('date', [now()->startOfMonth(), now()->endOfMonth()])->get();
        if($this->id == '6'){
            // dd($workdays);
        }
        $workhours = 0;
        foreach ($workdays as $workday) {
            $workhours += $workday->workhours;
        }
        return $workhours;
    }

    public function workdays_last_month()
    {
        return $this->workdays()->whereBetween('date', [now()->startOfMonth(), now()->endOfMonth()])->where('workhours', '>', '0')->count();
    }

    public function getShiftId()
    {
        $shift = Shift::find($this->shift);
        if($shift) return $shift->id;

    }
}

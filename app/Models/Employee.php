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
        'salary_net',
        'salary_gross',
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
}

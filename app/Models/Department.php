<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Department extends Model
{
    use HasFactory;

    //fillable
    protected $fillable = [
        'title',
        'owner_id',
        'company_id',
        'branch_id',
    ];

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

    public function groups()
    {
        return $this->belongsToMany(Group::class);
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    //zams DepartmentEmployee
   
    public function zams()
    {
        return $this->belongsToMany(Employee::class, 'department_employees', 'department_id', 'employee_id');
    }

    //company
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}

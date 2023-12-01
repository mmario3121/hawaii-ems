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
    ];

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

    public function groups()
    {
        return $this->hasMany(Group::class);
    }

    public function owner()
    {
        return $this->belongsTo(Employee::class, 'owner_id');
    }

    //zams DepartmentEmployee
   
    public function zams()
    {
        return $this->belongsToMany(Employee::class, 'department_employees', 'department_id', 'employee_id');
    }
}

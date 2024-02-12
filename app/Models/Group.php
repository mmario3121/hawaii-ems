<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    protected $fillable = [
        'department_id',
        'name'
    ];

    public function departments()
    {
        return $this->belongsToMany(Department::class);
    }

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }
}

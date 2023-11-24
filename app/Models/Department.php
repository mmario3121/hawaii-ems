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
    ];

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

    public function groups()
    {
        return $this->hasMany(Group::class);
    }
}

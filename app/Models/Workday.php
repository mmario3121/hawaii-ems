<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Workday extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'date',
        'workhours',
        'overtime',
        'sickleave',
        'vacation',
        'holiday',
        'isWorkday'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}

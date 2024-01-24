<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'start_date',
        'start_time',
        'end_time',
        'break',
        'work_days',
        'vacation_days',
        'hours'
    ];

    public function shift_hours()
    {
        // end_time - start_time - break
        //end_time and start_time are hh:mm
        //break is in hours
        $start_time = explode(':', $this->start_time);
        $end_time = explode(':', $this->end_time);
        $break = $this->break;
        $hours = $end_time[0] - $start_time[0] - $break;
        return $hours;
    }


}

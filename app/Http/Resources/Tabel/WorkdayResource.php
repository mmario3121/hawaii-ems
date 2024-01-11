<?php

namespace App\Http\Resources\Tabel;

use App\Http\Resources\AbsenceResource;
use App\Http\Resources\HolidayResource;
use App\Models\Absence;
use App\Models\Holiday;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WorkdayResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        //holiday which starts before this workday and ends after this workday
        $holiday = Holiday::where('start_date', '<=', $this->date)->where('end_date', '>=', $this->date)->first();
        return [
            'id' => $this->id,
            'date' => $this->date,
            'workhours' => $this->workhours,
            'overtime' => $this->overtime,
            'sickleave' => $this->sickleave,
            'vacation' => $this->vacation,
            'holiday' => new HolidayResource($holiday),
            'isWorkday' => $this->isWorkday,
            'absence' => new AbsenceResource(Absence::find($this->absence_id)),

        ];
    }
}

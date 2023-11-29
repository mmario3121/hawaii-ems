<?php

namespace App\Http\Resources\Tabel;

use App\Http\Resources\AbsenceResource;
use App\Models\Absence;
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
        return [
            'id' => $this->id,
            'date' => $this->date,
            'workhours' => $this->workhours,
            'overtime' => $this->overtime,
            'sickleave' => $this->sickleave,
            'vacation' => $this->vacation,
            'holiday' => $this->holiday,
            'isWorkday' => $this->isWorkday,
            'absence' => new AbsenceResource(Absence::find($this->absence_id)),
        ];
    }
}

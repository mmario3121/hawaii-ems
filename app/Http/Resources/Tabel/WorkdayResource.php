<?php

namespace App\Http\Resources\Tabel;

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
        ];
    }
}

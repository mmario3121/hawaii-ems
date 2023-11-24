<?php

namespace App\Http\Resources\Tabel;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'position' => $this->position->title,
            'company' => $this->company->title,
            'shift' => $this->shift,
            'department' => $this->department->title,
            'group' => $this->group->name,
            'salary_net' => $this->salary_net,
            'salary_gross' => $this->salary_gross,
            'workdays' => WorkdayResource::collection($this->whenLoaded('workdays')),
        ];
    }
}

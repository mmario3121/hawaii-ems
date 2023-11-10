<?php

namespace App\Http\Resources\Tabel;

use App\Http\Resources\Tabel\EmployeeResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DepartmentResource extends JsonResource
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
            'title' => $this->title,
            'employees' => EmployeeResource::collection($this->whenLoaded('employees')),
        ];
    }
}

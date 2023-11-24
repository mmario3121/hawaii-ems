<?php

namespace App\Http\Resources\Tabel;

use App\Http\Resources\Tabel\EmployeeResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GroupResource extends JsonResource
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
            'name' => $this->name,
            'employees' => EmployeeResource::collection($this->whenLoaded('employees')),
        ];
    }
}

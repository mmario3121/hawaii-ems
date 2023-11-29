<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeIdResource extends JsonResource
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
            'birthday' => $this->birthday,
            'phone' => $this->phone,
            'position_id' => $this->position_id,
            'iin' => $this->iin,
            'email' => $this->email,
            'company_id' => $this->company_id,
            'group_id' => $this->group_id,
            'shift' => $this->shift,
            'department_id' => $this->department_id,
            'salary_net' => $this->salary_net,
            'salary_gross' => $this->salary_gross,
            'number' => $this->number,
        ];
    }
}

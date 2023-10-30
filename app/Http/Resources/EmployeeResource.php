<?php

namespace App\Http\Resources;

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
            'birthday' => $this->birthday,
            'phone' => $this->phone,
            'position_id' => $this->getPosition->title,
            'iin' => $this->iin,
            'email' => $this->email,
            'company_id' => $this->getCompany->title,
            'shift' => $this->shift,
            'department_id' => $this->getDepartment->title,
            'salary_net' => $this->salary_net,
            'salary_gross' => $this->salary_gross,
        ];
    }
}

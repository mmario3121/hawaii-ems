<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Company;
class EmployeeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $company = Company::find($this->department->company_id);
        if($company) {
            $company_title = $company->title;
        } else {
            $company_title = '';
        }
        return [
            'id' => $this->id,
            'name' => $this->name,
            'birthday' => $this->birthday,
            'phone' => $this->phone,
            'position_id' => $this->position->title,
            'iin' => $this->iin,
            'email' => $this->email,
            'company_id' => $company_title,
            'group_id' => $this->group_id,
            'shift' => $this->shift,
            'department_id' => $this->department->title,
            'salary_net' => $this->salary_net,
            'salary_gross' => $this->salary_gross,
            'number' => $this->number,
        ];
    }
}

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
            'position' => $this->position->title,
            'iin' => $this->iin,
            'email' => $this->email,
            'company' => $company_title,
            'group' => $this->group->title,
            'shift' => $this->shift,
            'department' => $this->department->title,
            'salary_net' => $this->salary_net,
            'salary_gross' => $this->salary_gross,
            'number' => $this->number,
        ];
    }
}

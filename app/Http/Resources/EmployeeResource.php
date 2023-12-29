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
        $company = Company::find($this->company_id);
        if($company) {
            $company_title = $company->title;
        } else {
            $company_title = '';
        }

        if($this->city)
        {
            $city_title = $this->city->title;
        } else {
            $city_title = '';
        }
        if($this->group)
        {
            $group_title = $this->group->name;
        } else {
            $group_title = '';
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
            'group' => $group_title,
            'shift' => $this->shift,
            'department' => $this->department->title,
            'salary_net' => $this->salary_net,
            'salary_gross' => $this->salary_gross,
            'number' => $this->number,
            'city' => $city_title,
            'address' => $this->address,
            'workhours_count' => $this->workhours(),
            'workdays_count' => $this->workdays_last_month(),
        ];
    }
}

<?php

namespace App\Http\Resources\Tabel;

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
        $group = $this->group;
        if($group) {
            $group_name = $group->name;
        } else {
            $group_name = '';
        }
        return [
            'id' => $this->id,
            'name' => $this->name,
            'position' => $this->position->title,
            'company' => $company_title,
            'shift' => $this->shift,
            'department' => $this->department->title,
            'group' => $group_name,
            'salary_net' => $this->salary_net,
            'salary_gross' => $this->salary_gross,
            'number' => $this->number,
            'workdays' => WorkdayResource::collection($this->whenLoaded('workdays')),
            'workhours_count' => $this->workhours(),
            'workdays_count' => $this->workdays_last_month()
        ];
    }
}

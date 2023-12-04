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
        return [
            'id' => $this->id,
            'name' => $this->name,
            'position' => $this->position->title,
            'company' => $company_title,
            'shift' => $this->shift,
            'department' => $this->department->title,
            'group' => $this->group->name,
            'salary_net' => $this->salary_net,
            'salary_gross' => $this->salary_gross,
            'number' => $this->number,
            'workdays' => WorkdayResource::collection($this->whenLoaded('workdays')),
            'workhours' => $this->workhours(),
        ];
    }
}

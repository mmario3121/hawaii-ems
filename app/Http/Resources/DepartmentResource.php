<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DepartmentResource extends JsonResource
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
            $company_bin = $company->bin;
        } else {
            $company_bin = '';
        }
        return [
            'id' => $this->id,
            'title' => $this->title,
            'bin' => $company_bin,
            'owner' => new EmployeeResource($this->whenLoaded('owner')),
            'zams' => EmployeeResource::collection($this->whenLoaded('zams')),
            'groups' => GroupResource::collection($this->whenLoaded('groups')), 
        ];
    }
}

<?php

namespace App\Http\Resources\Tabel;

use App\Http\Resources\Tabel\EmployeeResource;
use App\Models\Company;
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
            'groups' => GroupResource::collection($this->whenLoaded('groups')),
        ];
    }
}

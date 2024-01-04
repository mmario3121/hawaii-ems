<?php

namespace App\Http\Resources\Tabel;

use App\Http\Resources\Tabel\EmployeeResource;
use App\Models\Company;
use App\Models\Holiday;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\HolidayResource;

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
        $yearMonth = $request->input('year_month'); // предполагается, что 'year_month' - это ключ в запросе
        [$year, $month] = explode('-', $yearMonth);
        $holidays = Holiday::whereYear('date', $year)
                               ->whereMonth('date', $month)
                               ->get();
        return [
            'id' => $this->id,
            'title' => $this->title,
            'bin' => $company_bin,
            'owner' => new EmployeeResource($this->whenLoaded('owner')),
            'groups' => GroupResource::collection($this->whenLoaded('groups')),
            'holidays' => HolidayResource::collection($holidays),
        ];
    }
}

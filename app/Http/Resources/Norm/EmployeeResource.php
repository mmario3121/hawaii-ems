<?php

namespace App\Http\Resources\Norm;

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
        if($company) {
            $bin = $company->bin;
        } else {
            $bin = '';
        }
        $group = $this->group;
        if($group) {
            $group_name = $group->name;
        } else {
            $group_name = '';
        }
        $city = $this->city;
        if($city) {
            $city_title = $city->title;
        } else {
            $city_title = '';
        }
        if($this->workhours($request->year_month) > $this->norm($request->year_month)) {
            $norm_worked = $this->norm($request->year_month);
        } else {
            $norm_worked = $this->workhours($request->year_month);
        }
        if($this->workhours($request->year_month) > $this->norm($request->year_month)) {
            $overtime = $this->workhours($request->year_month) - $this->norm($request->year_month);
        } else {
            $overtime = 0;
        }
        $norm_salary = number_format($norm_worked * $this->hourly_rate($request->year_month), 2, '.', '');

        //overtime_salary integer
        $overtime_salary = number_format($overtime * $this->hourly_rate($request->year_month), 2, '.', '');
        //round to integer
        $overtime_salary = round($overtime_salary);
        return [
            'id' => $this->id,
            'name' => $this->name,
            'position' => $this->position->title,
            'company' => $company_title,
            'shift' => $this->getShiftName(),
            'department' => $this->department->title,
            'group' => $group_name,
            'salary_net' => $this->salary_net,
            'salary_gross' => $this->salary_gross,
            'number' => $this->number,
            'workhours_count' => $this->workhours($request->year_month),
            'workdays_count' => $this->workdays_last_month($request->year_month),
            'norm' => $this->norm($request->year_month),
            'norm_worked' => $norm_worked,
            'norm_days' => $this->norm_days($request->year_month),
            'norm_salary' => $norm_salary,
            'hourly_rate' => $this->hourly_rate($request->year_month),
            'overtime' => $overtime,
            'overtime_salary' => $overtime_salary,
            'city' => $city_title,
            'address' => $this->address,
            'bin' => $bin,
            'shift_hours' => $this->getShift()->shift_hours(),
        ];
    }
}

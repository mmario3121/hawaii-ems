<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEmployeeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'id' => 'required|integer',
            'name' => 'required|max:255',
            'birthday' => 'required|date',
            'phone' => 'required|max:255',
            'position_id' => 'required|integer',
            'iin' => 'nullable|max:255',
            'email' => 'required|max:255',
            // 'company_id' => 'required|integer',
            'shift' => 'required|max:255',
            'department_id' => 'required|integer',
            'salary_net' => 'nullable|integer',
            'salary_gross' => 'nullable|integer',
            'group_id' => '',
            'number' => '',
            'bin' => 'max:255',
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreShiftRequest extends FormRequest
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
            //name, start_date, start_time, end_time, break, work_days, vacation_days
            'name' => 'required|max:255',
            'start_date' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
            'break' => 'required|integer',
            'work_days' => 'required|integer',
            'vacation_days' => 'required|integer',
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUserRequest extends FormRequest
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
            //
            'email' => 'required|max:255',
            'password' =>  'required|max:255',
            'name' => 'required|max:255',
            //role one of admin|hr|treasurer|manager
            'role' => [
                'required',
                'string',
                Rule::in(['hr', 'treasurer', 'manager']),
            ],
        ];
    }
}

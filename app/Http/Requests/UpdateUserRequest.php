<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
            'password' =>  'max:255',
            'name' => 'required|max:255',
            'id' => 'required|integer',
            'image' => 'file|image|max:1024',
            'role' => 'max:255',
            'branch_id' => 'required|exists:branches,id',
        ];
    }
}

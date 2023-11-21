<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

class LoginRequest extends FormRequest
{
    public function rules()
    {
        return [
            'email' => 'required|max:255',
            'password' => 'required|max:255',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'message' => trans('errors.error_validation'),
            'errors' => $validator->errors(),
        ], Response::HTTP_BAD_REQUEST));
    }
}

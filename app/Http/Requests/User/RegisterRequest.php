<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'mobile' => ['required', 'regex:/^01[0125][0-9]{8}$/'],
            'password' => "required",
            'name' => "required",
            'email' => "required",
        ];
    }
}

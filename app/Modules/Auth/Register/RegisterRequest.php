<?php

namespace App\Modules\Auth\Register;

use Core\Http\Requests\FormRequest;

class RegisterRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'email' => 'required|string|email',
            'password' => 'required|string',
            'otp' => 'required|numeric',
        ];
    }
}

<?php

namespace App\Modules\Auth\VerifyResetPassword;

use Core\Http\Requests\FormRequest;

class VerifyResetPasswordRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'token' => 'required|string',
        ];
    }
}

<?php

namespace App\Modules\Auth\VerifyResetPassword;

use Core\Http\Controllers\Controller;

class VerifyResetPasswordController extends Controller
{
    /**
     * @return mixed
     */
    public function __invoke(): mixed
    {
        return $this->serve(VerifyResetPasswordFeature::class);
    }
}

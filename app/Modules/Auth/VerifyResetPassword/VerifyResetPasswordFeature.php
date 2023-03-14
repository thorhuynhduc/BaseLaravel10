<?php

namespace App\Modules\Auth\VerifyResetPassword;

use Core\Services\BaseFeatures;
use Core\Models\OTPVerification;
use Illuminate\Http\JsonResponse;
use Throwable;

class VerifyResetPasswordFeature extends BaseFeatures
{
    /**
     * @param VerifyResetPasswordRequest $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function handle(VerifyResetPasswordRequest $request): JsonResponse
    {
        $token = $request->input('token');
        $this->run(ValidateResettableToken::class, [
            'token' => $token
        ]);

        return $this->success(true);
    }
}

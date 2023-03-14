<?php

namespace App\Modules\Auth\VerifyResetPassword;

use App\Enums\ExceptionCode;
use App\Exceptions\BusinessException;
use Core\Domains\BaseJob;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Throwable;

class ValidateResettableToken extends BaseJob
{
    /**
     * @param string $token
     */
    public function __construct(
        private string $token
    ) {
    }

    /**
     * @return void
     * @throws Throwable
     */
    public function handle(): void
    {
        $reset = DB::table('password_resets')
            ->where('token', $this->token)
            ->first();

        throw_if(
            !$reset,
            BusinessException::class,
            __('business.auth.wrong_otp'),
            ExceptionCode::WRONG_TOKEN
        );

        throw_if($this->tokenExpired($reset->created_at),
            BusinessException::class,
            __('business.auth.otp_expired'),
            ExceptionCode::TOKEN_EXPIRED
        );
    }

    /**
     * @param $createdAt
     * @return bool
     */
    protected function tokenExpired($createdAt): bool
    {
        $expires = config("auth.passwords.users.expire");

        return Carbon::parse($createdAt)->addSeconds($expires * 60)->isPast();
    }
}

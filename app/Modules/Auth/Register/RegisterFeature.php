<?php

namespace App\Modules\Auth\Register;

use App\Enums\ExceptionCode;
use App\Exceptions\BusinessException;
use Core\Services\BaseFeatures;
use Illuminate\Support\Facades\Hash;
use Core\Models\OTPVerification;
use Core\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use ReflectionException;
use Throwable;

class RegisterFeature extends BaseFeatures
{
    /**
     * @param RegisterRequest $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function handle(RegisterRequest $request): JsonResponse
    {
        $email = $request->input('email');
        $otp = $request->input('otp');

        $this->validateOTPCode($email, $otp);
        $this->validateUserUnique($request);
        $user = DB::transaction(function () use($request, $email, $otp) {
            $user = $this->registerUser($request);
            $this->generateUserSetting($user);
            $this->generateUserPackage($user);
            $this->generateUserInfo($user);
            $this->removeOTPCodeVerified($email, $otp);
            $this->updateShareLinkOfUser($user->id);

            return $user;
        });

        return $this->success(new UserTokenTransformer($user));
    }

    /**
     * @param RegisterRequest $request
     * @return void
     * @throws Throwable
     */
    protected function validateUserUnique(RegisterRequest $request): void
    {
        $user = $this->run(GetSimpleUserJob::class, ['email' => (string) $request->input('email')]);

        throw_if(
            $user,
            BusinessException::class,
            __('business.auth.email_exits'),
            ExceptionCode::REGISTER_EMAIL_UNIQUE
        );
    }

    /**
     * @param RegisterRequest $request
     * @return User
     * @throws Throwable
     */
    protected function registerUser(RegisterRequest $request): User
    {
        $data = [
            'email' => $request->input('email'),
            'password' => Hash::make((string) $request->input('password'))
        ];

        return DB::transaction(function () use ($data) {
            return $this->run(RegisterUserJob::class, [
                'data' => $data
            ]);
        });
    }

    /**
     * @param string $destination
     * @param string $otp
     * @return void
     * @throws Throwable
     */
    protected function validateOTPCode(string $destination, string $otp)
    {
        $verification = $this->run(ValidateOTPCodeJob::class, [
            'destination' => $destination,
            'type' => OTPVerification::EMAIL_VERIFY,
            'otp' => $otp
        ]);

        throw_if(
            !$verification,
            BusinessException::class,
            __('business.auth.wrong_otp'),
            ExceptionCode::WRONG_OTP
        );
    }

    /**
     * @param string $destination
     * @param string $otp
     * @return void
     * @throws ReflectionException
     */
    protected function removeOTPCodeVerified(string $destination, string $otp)
    {
        $this->runInQueue(RemoveOTPCodeVerifiedJob::class, [
            'destination' => $destination,
            'type' => OTPVerification::EMAIL_VERIFY,
            'otp' => $otp
        ]);
    }

    /**
     * Generate user setting default
     *
     * @param User $user
     */
    private function generateUserSetting(User $user)
    {
        $this->run(GenerateUserSettingJob::class, [
            'user' => $user
        ]);
    }

    /**
     * Generate user setting default
     *
     * @param User $user
     */
    private function generateUserPackage(User $user)
    {
        $this->run(GenerateUserPackageJob::class, [
            'user' => $user
        ]);
    }

    private function generateUserInfo(User $user)
    {
        $this->run(GenerateUserInformation::class, ['user' => $user]);
    }

    private function updateShareLinkOfUser(int $id)
    {
        $this->run(UpdateShareLinkOfUserJob::class, ['id' => $id]);
    }
}

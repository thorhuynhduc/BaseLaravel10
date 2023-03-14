<?php


namespace App\Modules\Auth\Register;


use Core\Domains\BaseJob;
use Core\Models\OTPVerification;
use Illuminate\Database\Eloquent\Model;

class ValidateOTPCodeJob extends BaseJob
{
    /**
     * @param string $destination
     * @param string $type
     * @param string $otp
     */
    public function __construct(
        private string $destination,
        private string $type,
        private string $otp
    ) {
    }

    /**
     * @return OTPVerification|Model|null
     */
    function handle(): OTPVerification|Model|null
    {
        return OTPVerification::query()->where([
            'destination' => $this->destination,
            'type' => $this->type,
            'otp' => $this->otp,
            'status' => OTPVerification::SUBMIT_STATUS
        ])->first();
    }
}

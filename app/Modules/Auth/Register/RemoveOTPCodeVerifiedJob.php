<?php


namespace App\Modules\Auth\Register;


use Core\Domains\BaseQueueableJob;
use Core\Models\OTPVerification;

class RemoveOTPCodeVerifiedJob extends BaseQueueableJob
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
     * @return boolean
     */
    function handle(): bool
    {
        return OTPVerification::query()->where([
            'destination' => $this->destination,
            'type' => $this->type,
            'otp' => $this->otp,
            'status' => OTPVerification::SUBMIT_STATUS
        ])->delete();
    }
}

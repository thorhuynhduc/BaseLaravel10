<?php


namespace App\Modules\Auth\Register;


use Core\Domains\BaseJob;
use Core\Models\User;
use Core\Models\UserInformation;

class GenerateUserInformation extends BaseJob
{

    /**
     * @param User $user
     */
    public function __construct(
        private User $user
    ) {
    }

    public function handle()
    {
        UserInformation::create(['user_id' => $this->user->id]);
    }
}
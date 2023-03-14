<?php

namespace App\Modules\Auth\Login;

use Core\Models\User;
use Core\Domains\BaseJob;

class GetSimpleUserJob extends BaseJob
{
    /**
     * @param string $email
     */
    public function __construct(private string $email)
    {
    }

    /**
     * @return User|null
     */
    public function handle(): User|null
    {
        return User::query()->where('email', $this->email)->first();
    }
}

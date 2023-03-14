<?php


namespace App\Modules\Auth\Register;


use Core\Domains\BaseJob;
use Core\Models\User;

class RegisterUserJob extends BaseJob
{
    /**
     * @param array $data
     */
    public function __construct(
        private array $data
    ) {
    }

    /**
     * @return User
     */
    function handle(): User
    {
        $this->data['uuid'] = $this->generateUuid();

        return User::create($this->data);
    }

    private function generateUuid()
    {
        $uuid      = generate_uuid_v4();
        $isExisted = User::where('uuid', $uuid)->first();

        if (!empty($isExisted)) {
            return $this->generateUuid();
        }

        return $uuid;
    }
}

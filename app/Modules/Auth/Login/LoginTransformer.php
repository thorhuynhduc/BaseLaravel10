<?php

namespace App\Modules\Auth\Login;

use Core\Models\User;
use Core\Transformers\BaseTransformer;
use Illuminate\Support\Carbon;

class LoginTransformer extends BaseTransformer
{
    /**
     * @return array
     */
    public function data(): array
    {
        /** @var User $user */
        $user = $this->resource;

        return [
            'access_token' => $user->createToken('access_token')->plainTextToken,
            'token_type' => 'bearer',
            'expires_at' => Carbon::now()
                ->addMinutes(config('sanctum.expiration'))
                ->toAtomString(),
        ];
    }
}

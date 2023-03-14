<?php


namespace App\Modules\Auth\Register;


use Carbon\Carbon;
use Core\Transformers\BaseTransformer;

class UserTokenTransformer extends BaseTransformer
{

    public function data(): array
    {
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
<?php

namespace App\Modules\Auth\Me;

use Core\Services\BaseFeatures;
use Illuminate\Http\JsonResponse;

class MeFeature extends BaseFeatures
{
    /**
     * @return JsonResponse
     */
    public function handle(): JsonResponse
    {
        return $this->success(auth()->user());
    }
}

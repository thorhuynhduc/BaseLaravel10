<?php

namespace App\Modules\Auth\Me;

use Core\Http\Controllers\Controller;

class MeController extends Controller
{
    /**
     * @return mixed
     */
    public function __invoke(): mixed
    {
        return $this->serve(MeFeature::class);
    }
}

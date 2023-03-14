<?php


namespace App\Modules\Auth\Register;


use App\Enums\DynamicLinkObject;
use App\Services\DynamicLink\GenerateDynamicLinkService;
use Core\Domains\BaseJob;
use Core\Models\User;

class UpdateShareLinkOfUserJob extends BaseJob
{
    public function __construct(
        private int $id
    ) {
    }

    public function handle()
    {
        if (class_exists(GenerateDynamicLinkService::class)) {
            /* @var GenerateDynamicLinkService $service */
            $service = app(GenerateDynamicLinkService::class);
            $dynamicLink = $service->handle(DynamicLinkObject::USER, $this->id);

            if ($dynamicLink) {
                User::where('id', $this->id)->update(['profile_share_link' => $dynamicLink->getShortLink()]);
            }
        }
    }
}

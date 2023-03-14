<?php

namespace App\Modules\Auth\Register;

use Core\Domains\BaseJob;
use Core\Enums\AttributeEntityType;
use Core\Enums\UserSettingAttribute;
use Core\Models\Attribute;
use Core\Models\SettingUser;
use Core\Models\User;
use Core\Models\UserNotificationSetting;
use Illuminate\Support\Carbon;

class GenerateUserSettingJob extends BaseJob
{

    /**
     * @param User $user
     */
    public function __construct(private User $user)
    {
    }

    public function handle()
    {
        $attributes = Attribute::where('entity_type', AttributeEntityType::USER_SETTING)
            ->get();

        $upsert = [];

        foreach ($attributes as $attribute) {
            $upsert[] = [
                'attribute_id'    => $attribute->id,
                'user_id'         => $this->user->id,
                'attribute_value' => UserSettingAttribute::getDefaultValue($attribute->name),
                'created_at'      => Carbon::now(),
                'updated_at'      => Carbon::now(),
            ];
        }

        SettingUser::upsert($upsert, ['attribute_id', 'user_id'], ['attribute_value']);

        UserNotificationSetting::create([
            'user_id'             => $this->user->id,
            'on_messages'         => true,
            'on_admires'          => true,
            'on_matches'          => true,
            'on_expiring_matches' => true,
            'on_lofi_event'       => true,
        ]);
    }
}

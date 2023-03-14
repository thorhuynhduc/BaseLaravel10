<?php

namespace Core\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

/**
 * \Core\Models\SettingUser
 *
 * @property int $id
 * @property int $user_id
 * @property boolean $agree_all_terms_and_conditions
 * @property boolean $agree_terms_of_use
 * @property boolean $agree_personal_info
 * @property boolean $agree_receive_email_and_sms
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|SettingUser newModelQuery()
 * @method static Builder|SettingUser newQuery()
 * @method static Builder|SettingUser query()
 * @method static Builder|SettingUser whereAttributeId($value)
 * @method static Builder|SettingUser whereAttributeValue($value)
 * @method static Builder|SettingUser whereCreatedAt($value)
 * @method static Builder|SettingUser whereId($value)
 * @method static Builder|SettingUser whereUpdatedAt($value)
 * @method static Builder|SettingUser whereUserId($value)
 * @mixin Eloquent
 */
class SettingUser extends BaseModel
{
    protected $table = 'setting_users';

    protected $fillable = [
        'user_id',
        'agree_all_terms_and_conditions',
        'agree_terms_of_use',
        'agree_personal_info',
        'agree_receive_email_and_sms',
    ];
}
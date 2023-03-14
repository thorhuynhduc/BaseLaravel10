<?php

namespace Core\Models;

use App\Enums\FileableType;
use Core\Enums\AttributeEntityType;
use Core\Enums\UserSettingAttribute;
use Database\Factories\Core\Models\UserFactory;
use Eloquent;
use Exception;
use Geometry;
use geoPHP;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\DatabaseNotificationCollection;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Sanctum\PersonalAccessToken;

/**
 * Core\Models\User
 *
 * @property int $id
 * @property string $email
 * @property string $password
 * @property string|null $fullname
 * @property string|null $phone
 * @property string|null $bio
 * @property string|null $birthday
 * @property int|null $gender 1: Female; 2: Male; 3: Other
 * @property int $status 1: Active
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $last_active
 * @property string $location
 * @property string|null $address
 * @property string $uuid
 * @property bool $info_completed
 * @property string|null $city
 * @property int $active_status 0: Inactive, 1: Active
 * @property string|null $country
 * @property string|null $snooze_at
 * @property int|null $spotlight_at
 * @property string|null $hometown_address
 * @property string|null $lat
 * @property string|null $long
 * @property mixed|null $show_mee
 * @property array $show_me 1: Female; 2: Male; 3: Other
 * @property int $spotlight
 * @property Carbon|null $deleted_at
 * @property string|null $profile_share_link
 * @property-read int $distance
 * @property-read FileManagement|null $avatar
 * @property-read Collection|Attribute[] $basics
 * @property-read int|null $basics_count
 * @property-read Collection|Creativity[] $creativities
 * @property-read int|null $creativities_count
 * @property-read string|null $share_link
 * @property-read UserInformation|null $info
 * @property-read Package|null $lastPackage
 * @property-read Collection|UserLike[] $likes
 * @property-read int|null $likes_count
 * @property-read Collection|UserMatch[] $matches
 * @property-read int|null $matches_count
 * @property-read Collection|UserNope[] $nopes
 * @property-read int|null $nopes_count
 * @property-read UserNotificationSetting|null $notificationSetting
 * @property-read DatabaseNotificationCollection|DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read Collection|Package[] $packages
 * @property-read int|null $packages_count
 * @property-read Collection|Passions[] $passions
 * @property-read int|null $passions_count
 * @property-read Collection|Pet[] $pets
 * @property-read int|null $pets_count
 * @property-read Collection|FileManagement[] $photos
 * @property-read int|null $photos_count
 * @property-read Collection|UserReactionLog[] $reactions
 * @property-read int|null $reactions_count
 * @property-write mixed $hometown_location
 * @property-read Collection|Attribute[] $settings
 * @property-read int|null $settings_count
 * @property-read Collection|SexualOrientation[] $sexualOrientation
 * @property-read int|null $sexual_orientation_count
 * @property-read Collection|Sport[] $sports
 * @property-read int|null $sports_count
 * @property-read Collection|UserSupperLike[] $superLikes
 * @property-read int|null $super_likes_count
 * @property-read Collection|PersonalAccessToken[] $tokens
 * @property-read int|null $tokens_count
 * @property-read Collection|UserNotificationToken[] $tokensPush
 * @property-read int|null $tokens_push_count
 * @property-read UserTravelAddress|null $travelAddress
 * @property-read Collection|Traveling[] $travelings
 * @property-read int|null $travelings_count
 * @method static UserFactory factory(...$parameters)
 * @method static Builder|User newModelQuery()
 * @method static Builder|User newQuery()
 * @method static \Illuminate\Database\Query\Builder|User onlyTrashed()
 * @method static Builder|User query()
 * @method static Builder|User whereActiveStatus($value)
 * @method static Builder|User whereAddress($value)
 * @method static Builder|User whereBio($value)
 * @method static Builder|User whereBirthday($value)
 * @method static Builder|User whereCity($value)
 * @method static Builder|User whereCountry($value)
 * @method static Builder|User whereCreatedAt($value)
 * @method static Builder|User whereDeletedAt($value)
 * @method static Builder|User whereEmail($value)
 * @method static Builder|User whereFullname($value)
 * @method static Builder|User whereGender($value)
 * @method static Builder|User whereHometownAddress($value)
 * @method static Builder|User whereId($value)
 * @method static Builder|User whereInfoCompleted($value)
 * @method static Builder|User whereLastActive($value)
 * @method static Builder|User whereLat($value)
 * @method static Builder|User whereLocation($value)
 * @method static Builder|User whereLong($value)
 * @method static Builder|User wherePassword($value)
 * @method static Builder|User wherePhone($value)
 * @method static Builder|User whereProfileShareLink($value)
 * @method static Builder|User whereShowMe($value)
 * @method static Builder|User whereShowMeBk($value)
 * @method static Builder|User whereShowMee($value)
 * @method static Builder|User whereSnoozeAt($value)
 * @method static Builder|User whereSpotlight($value)
 * @method static Builder|User whereSpotlightAt($value)
 * @method static Builder|User whereStatus($value)
 * @method static Builder|User whereUpdatedAt($value)
 * @method static Builder|User whereUuid($value)
 * @method static \Illuminate\Database\Query\Builder|User withTrashed()
 * @method static \Illuminate\Database\Query\Builder|User withoutTrashed()
 * @mixin Eloquent
 * @property-read Order|null $order
 */
class User extends BaseModel implements AuthenticatableContract, CanResetPasswordContract
{
    use HasApiTokens;
    use HasFactory;
    use Authenticatable;
    use CanResetPassword;
    use Notifiable;
    use SoftDeletes;

    public const FEMALE = 1;
    public const MALE   = 2;
    public const OTHER  = 3;

    public const GENDER = [
        self::MALE,
        self::FEMALE,
        self::OTHER,
    ];

    public const INACTIVE_STATUS = 0;
    public const ACTIVE_STATUS = 1;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'email',
        'password',
        'fullname',
        'phone',
        'birthday',
        'gender',
        'status',
        'show_me',
        'location',
        'address',
        'last_active',
        'is_online',
        'bio',
        'info_completed',
        'uuid',
        'city',
        'snooze_at',
        'country',
        'hometown_address',
        'lat',
        'long',
        'spotlight',
        'profile_share_link',
    ];

    protected $casts = [
        'show_me' => 'json'
    ];

    /**
     * @return HasManyThrough
     */
    public function sexualOrientation(): HasManyThrough
    {
        return $this->hasManyThrough(
            SexualOrientation::class,
            UserSexualOrientation::class,
            'user_id',
            'id',
            'id',
            'sexual_orientation_id',
        );
    }

    /**
     * @return HasManyThrough
     */
    public function passions(): HasManyThrough
    {
        return $this->hasManyThrough(
            Passions::class,
            UserPassions::class,
            'user_id',
            'id',
            'id',
            'passion_id',
        );
    }

    /**
     * @return HasManyThrough
     */
    public function creativities(): HasManyThrough
    {
        return $this->hasManyThrough(
            Creativity::class,
            UserCreativities::class,
            'user_id',
            'id',
            'id',
            'creativity_id',
        );
    }

    /**
     * @return HasManyThrough
     */
    public function pets(): HasManyThrough
    {
        return $this->hasManyThrough(
            Pet::class,
            UserPets::class,
            'user_id',
            'id',
            'id',
            'pet_id',
        );
    }

    /**
     * @return HasManyThrough
     */
    public function travelings(): HasManyThrough
    {
        return $this->hasManyThrough(
            Traveling::class,
            UserTravelings::class,
            'user_id',
            'id',
            'id',
            'traveling_id',
        );
    }

    /**
     * @return HasManyThrough
     */
    public function sports(): HasManyThrough
    {
        return $this->hasManyThrough(
            Sport::class,
            UserSports::class,
            'user_id',
            'id',
            'id',
            'sport_id',
        );
    }

    /**
     * @return HasOne
     */
    public function avatar(): HasOne
    {
        return $this->hasOne(FileManagement::class, 'fileable_id')
            ->where('fileable_type', FileableType::USER_AVATAR);
    }

    /**
     * @return BelongsToMany
     */
    public function settings(): BelongsToMany
    {
        return $this->belongsToMany(Attribute::class, 'setting_user')
            ->where('entity_type', AttributeEntityType::USER_SETTING)
            ->withPivot('attribute_value');
    }

    /**
     * @return BelongsToMany
     */
    public function basics(): BelongsToMany
    {
        return $this->belongsToMany(Attribute::class, 'user_basics')
            ->where('entity_type', AttributeEntityType::USER_BASICS)
            ->withPivot('attribute_value');
    }

    public function getSettings(): object
    {
        return (object) UserSettingAttribute::transformAttributes($this->settings->toArray());
    }

    /**
     * @return HasOne
     */
    public function info(): HasOne
    {
        return $this->hasOne(UserInformation::class, 'user_id');
    }

    /**
     * @return HasMany
     */
    public function photos(): HasMany
    {
        return $this->hasMany(FileManagement::class, 'fileable_id')
            ->where('fileable_type', FileableType::USER_PHOTO);
    }



    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];

    protected $dates = [
        'last_active',
    ];

    public function getLocationAttribute($value): string
    {
        $location = '';

        if (empty($value)) {
            return $location;
        }

        try {
            $geometryData = geoPHP::load($value);

            if ($geometryData instanceof Geometry) {
                $point = $geometryData->asArray();
                $location = implode(',', $point);
            }
        } catch (Exception $e) {
            Log::error('PARSE_USER_LOCATION_ERROR' . $e->getMessage());
        }

        return $location;
    }

    public function getBirthdayAttribute($value): ?string
    {
        if ($value) {
            return Carbon::parse($value)->format('Y-m-d');
        }

        return null;
    }

    /**
     * @param $value
     */
    public function setHometownLocationAttribute($value)
    {
        if (!str_contains($value, 'POINT') && !empty($value)) {
            $location = str_replace(',', ' ', $value);
            $this->attributes['hometown_location'] = "POINT($location)";
        } else {
            $this->attributes['hometown_location'] = $value;
        }
    }
    /**
     * @param $value
     */
    public function setLocationAttribute($value)
    {
        if (!str_contains($value, 'POINT')) {
            $location = str_replace(',', ' ', $value);
            $this->attributes['location'] = "POINT($location)";
        } else {
            $this->attributes['location'] = $value;
        }
    }

    /**
     * @return HasMany
     */
    public function likes(): HasMany
    {
        return $this->hasMany(UserLike::class);
    }

    /**
     * @return HasMany
     */
    public function nopes(): HasMany
    {
        return $this->hasMany(UserNope::class);
    }

    /**
     * @return HasMany
     */
    public function superLikes(): HasMany
    {
        return $this->hasMany(UserSupperLike::class);
    }

    /**
     * @return HasMany
     */
    public function matches(): HasMany
    {
        return $this->hasMany(UserMatch::class);
    }

    /**
     * @return HasMany
     */
    public function reactions(): HasMany
    {
        return $this->hasMany(UserReactionLog::class);
    }

    /**
     * @return HasMany
     */
    public function tokensPush(): HasMany
    {
        return $this->hasMany(UserNotificationToken::class);
    }

    /**
     * @return HasOne
     */
    public function travelAddress(): HasOne
    {
        $now = Carbon::now()->format('Y-m-d H:i:s');

        return $this->hasOne(UserTravelAddress::class)->where('expired_at', '>=', $now);
    }

    /**
     * @return HasOne
     */
    public function notificationSetting(): HasOne
    {
        return $this->hasOne(UserNotificationSetting::class);
    }

    /**
     * @return BelongsToMany
     */
    public function packages(): BelongsToMany
    {
        return $this->belongsToMany(Package::class, 'package_user')
            ->withPivot([
                'activated_at',
                'expires_at',
                'finished_at',
            ]);
    }

    /**
     * @return HasOneThrough
     */
    public function lastPackage(): HasOneThrough
    {
        return $this->hasOneThrough(
            Package::class,
            PackageUser::class,
            'user_id',
            'id',
            'id',
            'package_id'
        )
            ->select([
                'package_user.activated_at',
                'package_user.expires_at',
                'package_user.finished_at',
                'package_user.duration',
                'package_user.amount_type',
                'package_user.price',
                'package_user.order_id',
                'packages.*',
            ])
            ->whereNull('package_user.finished_at')
            ->orderByDesc('package_user.id')
            ->limit(1);
    }
}

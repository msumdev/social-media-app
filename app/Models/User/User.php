<?php

namespace App\Models\User;

use App\Models\AppLog;
use App\Models\Asset;
use App\Models\City;
use App\Models\Country;
use App\Models\Gender;
use App\Models\Posts\Post;
use App\Models\Sex;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Redis;
use Laravel\Sanctum\HasApiTokens;
use MongoDB\Laravel\Eloquent\HybridRelations;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens, HasRoles, HybridRelations;

    /**
     * @var string $connection
     */
    protected $connection;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->connection = app()->runningUnitTests() ? config('database.default') : 'mysql';
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'online',
        'first_name',
        'last_name',
        'username',
        'email',
        'country_id',
        'city_id',
        'date_of_birth',
        'sex_id',
        'gender_id',
        'sexuality_id',
        'registered',
        'status',
        'description',
        'interests',
        'password',
        'last_registration_email_sent_at',
        'jwt_token',
        'jwt_token_expires_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'remember_token',
        'api_token',
        'role',
        'password_reset_token',
        'token',
        'password_reset_at',
        'email_verified_at',
        'created_at',
        'updated_at'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    public $with = [
        'profile_picture'
    ];

    /**
     * @var string[] $appends
     */
    protected $appends = [];

    /**
     * @var string[] $casts
     */
    protected $casts = [
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function friends(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'friends', 'user_id', 'friend_id')
            ->withTimestamps();
    }

    /**
     * @return HasOne
     */
    public function country(): HasOne
    {
        return $this->hasOne(Country::class, 'id', 'country_id');
    }

    /**
     * @return HasOne
     */
    public function city(): HasOne
    {
        return $this->hasOne(City::class, 'id', 'city_id');
    }

    /**
     * @return HasOne
     */
    public function filters(): HasOne
    {
        return $this->hasOne(UserFilter::class, 'user_id', 'id');
    }

    /**
     * @return BelongsToMany
     */
    public function interests(): BelongsToMany
    {
        return $this->belongsToMany(InterestType::class, 'interests', 'user', 'interest_type_id');
    }

    /**
     * @return BelongsToMany
     */
    public function languages(): BelongsToMany
    {
        return $this->belongsToMany(LanguageType::class, 'languages', 'user', 'language_type_id');
    }

    /**
     * @return int
     */
    public function getAgeAttribute(): int
    {
        return Carbon::parse($this->date_of_birth)->age;
    }

    /**
     * @return HasOne
     */
    public function profile_picture(): HasOne
    {
        return $this->hasOne(Asset::class, 'user_id')->where('type', Asset::PROFILE_PICTURE);
    }

    /**
     * @return HasMany
     */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class, 'user', 'id');
    }


    /**
     * @return HasOne
     */
    public function profile(): HasOne
    {
        return $this->hasOne(UserProfile::class, 'user', 'id');
    }

    /**
     * @return HasOne
     */
    public function gender(): HasOne
    {
        return $this->hasOne(Gender::class, 'id', 'gender_id');
    }

    /**
     * @return HasOne
     */
    public function sex(): HasOne
    {
        return $this->hasOne(Sex::class, 'id', 'sex_id');
    }

    /**
     * @return HasOne
     */
    public function sexuality(): HasOne
    {
        return $this->hasOne(SexualityType::class, 'id', 'sexuality_id');
    }

    /**
     * @return int
     */
    public function getFollowerCountAttribute(): int
    {
        return $this->followers()->count();
    }

    /**
     * @return HasMany
     */
    public function followers(): HasMany
    {
        return $this->hasMany(Follower::class, 'user_id', 'id');
    }

    /**
     * @return HasOne
     */
    public function settings(): HasOne
    {
        return $this->hasOne(UserSetting::class, 'user_id', 'id');
    }

    /**
     * @return HasMany
     */
    public function comments(): HasMany
    {
        return $this->hasMany(ProfileComment::class, 'user', 'id');
    }

    /**
     * @return HasMany
     */
    public function logs(): HasMany
    {
        return $this->hasMany(AppLog::class, 'user', 'id');
    }

    /**
     * @return void
     */
    public function updateActivity(): void
    {
        Redis::set('user:' . $this->id . ':last_activity', now()->timestamp);
    }
}

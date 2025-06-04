<?php

namespace App\Models\User;

use App\Models\AppLog;
use App\Models\City;
use App\Models\Country;
use App\Models\Gender;
use App\Models\Posts\Post;
use App\Models\Sex;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Redis;
use Laravel\Sanctum\HasApiTokens;
use MongoDB\Laravel\Eloquent\HybridRelations;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, HasRoles, HybridRelations, Notifiable;

    /**
     * @var string
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
        'password',
        'last_registration_email_sent_at',
        'jwt_token',
        'jwt_token_expires_at',
    ];

    protected $hidden = [
        'password',
        'jwt_token',
    ];

    /**
     * @var string[]
     */
    protected $appends = [];

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

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function userFilter(): HasOne
    {
        return $this->hasOne(UserFilter::class);
    }

    public function interests(): HasMany
    {
        return $this->hasMany(Interest::class);
    }

    public function languages(): HasMany
    {
        return $this->hasMany(Language::class);
    }

    public function getAgeAttribute(): int
    {
        return Carbon::parse($this->date_of_birth)->age;
    }

    public function profilePicture(): HasOne
    {
        return $this->hasOne(ProfilePicture::class);
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class, 'user', 'id');
    }

    public function userProfile(): HasOne
    {
        return $this->hasOne(UserProfile::class);
    }

    public function gender(): BelongsTo
    {
        return $this->belongsTo(Gender::class);
    }

    public function sex(): BelongsTo
    {
        return $this->belongsTo(Sex::class);
    }

    public function sexuality(): BelongsTo
    {
        return $this->belongsTo(SexualityType::class);
    }

    public function followers(): HasMany
    {
        return $this->hasMany(Follower::class);
    }

    public function userSetting(): HasOne
    {
        return $this->hasOne(UserSetting::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(ProfileComment::class);
    }

    public function logs(): HasMany
    {
        return $this->hasMany(AppLog::class);
    }

    public function updateActivity(): void
    {
        Redis::set('user:'.$this->id.':last_activity', now()->timestamp);
    }

    public function blockedUsers(): HasMany
    {
        return $this->hasMany(BlockedUser::class, 'user_id', 'id');
    }

    public function hasBlocked(User $user): bool
    {
        return BlockedUser::where('blocked_user_id', $user->id)->where('user_id', $this->id)->exists();
    }
}

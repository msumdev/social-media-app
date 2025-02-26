<?php

namespace App\Models\Posts;

use App\Models\Asset;
use App\Models\User\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use MongoDB\Laravel\Eloquent\HybridRelations;
use MongoDB\Laravel\Eloquent\Model;

class Post extends Model
{
    use HasFactory, HybridRelations;

    /**
     * @var string $connection
     */
    protected $connection = 'mongodb';

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        if (app()->runningUnitTests()) {
            $this->connection = 'mongodb_testing';
        }
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'content',
        'hashtags',
        'mentions'
    ];

    /**
     * @var string[] $appends
     */
    protected $appends = [
        'comment_count',
        'created_at_title',
        'created_at_display',
        'like_count',
        'liked_by_user',
    ];

    /**
     * @var string[] $with
     */
    protected $with = [
        'user',
        'assets'
    ];

    /**
     * @var string[] $hidden
     */
    protected $hidden = [
        'pivot'
    ];

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * @return HasMany
     */
    public function assets(): HasMany
    {
        return $this->hasMany(Asset::class, 'post_id');
    }

    /**
     * @return string
     */
    public function getCreatedAtTitleAttribute()
    {
        return Carbon::parse($this->created_at)->format('d M Y H:i');
    }

    /**
     * @return string
     */
    public function getCreatedAtDisplayAttribute()
    {
        return Carbon::parse($this->created_at)->diffForHumans();
    }

    /**
     * @return HasMany
     */
    public function comments(): HasMany
    {
        return $this->hasMany(PostComment::class, 'post_id')->orderBy('created_at', 'desc');
    }

    /**
     * @return int
     */
    public function getCommentCountAttribute(): int
    {
        return $this->comments()->count();
    }

    /**
     * @return HasMany
     */
    public function likes(): HasMany
    {
        return $this->hasMany(PostLike::class, 'post_id');
    }

    /**
     * @return int
     */
    public function getLikeCountAttribute(): int
    {
        return $this->likes()->count();
    }

    /**
     * @return bool
     */
    public function getLikedByUserAttribute(): bool
    {
        return $this->likes()->where('user_id', auth()->id())->exists();
    }
}

<?php

namespace App\Models\Posts;

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
     * @var string
     */
    protected $connection = 'mongodb';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'content',
        'hashtags',
        'mentions',
    ];

    /**
     * @var string[]
     */
    protected $appends = [
        'comment_count',
        'created_at_title',
        'created_at_display',
        'like_count',
        'liked_by_user',
    ];

    /**
     * @var string[]
     */
    protected $with = [
        'user',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function postImageAssets(): HasMany
    {
        return $this->hasMany(PostImageAsset::class);
    }

    public function postAudioAssets(): HasMany
    {
        return $this->hasMany(PostImageAsset::class);
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

    public function comments(): HasMany
    {
        return $this->hasMany(PostComment::class);
    }

    public function getCommentCountAttribute(): int
    {
        return $this->comments()->count();
    }

    public function likes(): HasMany
    {
        return $this->hasMany(PostLike::class);
    }

    public function getLikeCountAttribute(): int
    {
        return $this->likes()->count();
    }

    public function getLikedByUserAttribute(): bool
    {
        return $this->likes()->where('user_id', auth()->id())->exists();
    }
}

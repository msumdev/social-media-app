<?php

namespace App\Models\Posts;

use App\Models\User\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use MongoDB\Laravel\Eloquent\HybridRelations;
use MongoDB\Laravel\Eloquent\Model;

class PostComment extends Model
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
        'post_id',
        'user_id',
        'content',
        'hashtags',
        'mentions',
    ];

    /**
     * @var string[]
     */
    protected $with = [
        'user',
    ];

    /**
     * @var string[]
     */
    protected $appends = [
        'created_at_title',
        'created_at_display',
    ];

    protected static function booted()
    {
        static::deleting(function (PostComment $comment) {
            $comment->postCommentReactions()->delete();
            $comment->postCommentAudioAssets()->delete();
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function postCommentReactions()
    {
        return $this->hasMany(PostCommentReaction::class);
    }

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function postCommentAudioAssets(): HasMany
    {
        return $this->hasMany(PostCommentAudioAsset::class);
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
}

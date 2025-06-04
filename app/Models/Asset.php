<?php

namespace App\Models;

use App\Models\Posts\Post;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use MongoDB\Laravel\Eloquent\Model;

class Asset extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $connection = 'mongodb';

    /**
     * @var string IMAGE
     */
    const POST_IMAGE = 'post-images';

    /**
     * @var string AUDIO
     */
    const POST_AUDIO = 'post-audio';

    /**
     * @var string PROFILE_PICTURE
     */
    const PROFILE_PICTURE = 'profile-pictures';

    /**
     * @var string POST_COMMENT_AUDIO
     */
    const POST_COMMENT_AUDIO = 'post-comment-audio';

    /**
     * @var string PROFILE_COMMENT_AUDIO
     */
    const PROFILE_COMMENT_AUDIO = 'profile-comment-audio';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user',
        'post_id',
        'post_comment_id',
        'profile_comment_id',
        'is_profile',
        'path',
        'type',
    ];

    /**
     * @var string[]
     */
    protected $appends = [
        'url',
    ];

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function getUrlAttribute(): string
    {
        return Storage::disk($this->type)->url($this->path);
    }

    public static function generateName(): string
    {
        return sha1(Str::random(16));
    }
}

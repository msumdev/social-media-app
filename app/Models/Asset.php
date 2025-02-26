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
     * @var string IMAGE
     */
    const POST_IMAGE = 'post-images';

    /**
     * @var string AUDIO
     */
    const POST_AUDIO = 'post-audios';

    /**
     * @var string PROFILE_PICTURE
     */
    const PROFILE_PICTURE = 'profile-pictures';

    /**
     * @var string POST_COMMENT_AUDIO
     */
    const POST_COMMENT_AUDIO = 'post-comment-audios';

    /**
     * @var string PROFILE_COMMENT_AUDIO
     */
    const PROFILE_COMMENT_AUDIO = 'profile-comment-audios';

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
        'type'
    ];

    /**
     * @var string[] $appends
     */
    protected $appends = [
        'url'
    ];

    /**
     * @return BelongsTo
     */
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    /**
     * @return string
     */
    public function getUrlAttribute(): string
    {
        return Storage::disk($this->type)->url($this->path);
    }

    /**
     * @return string
     */
    public static function generateName(): string
    {
        return sha1(Str::random(16));
    }
}

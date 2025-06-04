<?php

namespace App\Models\User;

use App\Models\Asset;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use MongoDB\Laravel\Eloquent\Model;

class ProfileComment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user',
        'content',
        'hashtags',
        'mentions',
    ];

    /**
     * @var string[]
     */
    protected $with = [
        'user',
        'likes',
        'assets',
    ];

    /**
     * @var string[]
     */
    protected $appends = [
        'created_at_title',
        'created_at_display',
    ];

    /**
     * @var string[]
     */
    protected $hidden = [
        'pivot',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user');
    }

    public function likes(): HasMany
    {
        return $this->hasMany(ProfileCommentLike::class, 'profile_comment_id');
    }

    public function assets(): HasMany
    {
        return $this->hasMany(Asset::class, 'profile_comment_id');
    }

    public function getCreatedAtTitleAttribute(): string
    {
        return Carbon::parse($this->created_at)->format('d M Y H:i');
    }

    public function getCreatedAtDisplayAttribute(): string
    {
        return Carbon::parse($this->created_at)->diffForHumans();
    }

    public static function getIndexName(): string
    {
        return (new self)->index;
    }
}

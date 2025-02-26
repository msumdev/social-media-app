<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use PDPhilip\Elasticsearch\Eloquent\HybridRelations;

class ProfileCommentLike extends Authenticatable
{
    use HasFactory, HybridRelations;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'profile_comment_id',
        'user',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [];

    /**
     * @var string[] $casts
     */
    protected $casts = [
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function comment(): BelongsToMany
    {
        return $this->belongsToMany(ProfileComment::class);
    }
}

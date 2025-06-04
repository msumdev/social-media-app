<?php

namespace App\Models;

use App\Models\User\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;
use MongoDB\Laravel\Relations\BelongsTo;

class AppLog extends Model
{
    use HasFactory;

    const PROFILE_VIEW = 1;

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
        'viewer_id',
        'viewed_id',
        'ip_address',
        'user_agent',
        'type',
    ];

    /**
     * @var string[]
     */
    protected $appends = [
        'created_at_title',
        'created_at_display',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'viewer_id');
    }

    public function profile(): BelongsTo
    {
        return $this->belongsTo(User::class, 'viewed_id');
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

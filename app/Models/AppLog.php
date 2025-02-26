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
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user',
        'profile',
        'ip_address',
        'user_agent',
        'type'
    ];

    /**
     * @var string[] $appends
     */
    protected $appends = [
        'created_at_title',
        'created_at_display'
    ];

    /**
     * @var string[] $with
     */
    protected $with = [
        'profile'
    ];

    /**
     * @var string[] $hidden
     */
    protected $hidden = [
        'ip_address',
        'user_agent'
    ];

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user');
    }

    /**
     * @return BelongsTo
     */
    public function profile(): BelongsTo
    {
        return $this->belongsTo(User::class, 'profile');
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
     * @param array $options
     * @return bool
     */
    public function save(array $options = [])
    {
        $this->setIndex('app-logs-' . date('Y-m'));

        return parent::save($options);
    }
}

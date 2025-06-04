<?php

namespace App\Models\Room;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use MongoDB\Laravel\Eloquent\Model;

class RoomMessage extends Model
{
    use HasFactory;

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
        'room_id',
        'author_id',
        'content',
    ];

    /**
     * @var string[]
     */
    protected $hidden = [];

    /**
     * @var string[]
     */
    protected $with = [];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function reports(): HasMany
    {
        return $this->hasMany(RoomMessageReport::class, 'message_id', '_id');
    }
}

<?php

namespace App\Models\Room;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RoomMessage extends Model
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
     * @var string[] $hidden
     */
    protected $hidden = [];

    /**
     * @var string[] $with
     */
    protected $with = [];

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * @return HasMany
     */
    public function reports(): HasMany
    {
        return $this->hasMany(RoomMessageReport::class, 'message_id', '_id');
    }
}

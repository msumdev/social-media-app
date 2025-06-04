<?php

namespace App\Models\Room;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use MongoDB\Laravel\Eloquent\HybridRelations;

class Room extends Model
{
    use HasFactory, HybridRelations;

    /**
     * @var string
     */
    protected $connection;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->connection = app()->runningUnitTests() ? config('database.default') : 'mysql';
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'hidden',
        'name',
        'type',
    ];

    /**
     * @var string[]
     */
    protected $hidden = [];

    /**
     * @var string[]
     */
    protected $with = ['members'];

    /**
     * @var string[]
     */
    protected $appends = ['owners'];

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'room_members', 'room_id', 'user_id');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(RoomMessage::class);
    }
}

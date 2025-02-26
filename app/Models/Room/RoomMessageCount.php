<?php

namespace App\Models\Room;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomMessageCount extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'room_id',
        'count'
    ];

    /**
     * @var string[] $hidden
     */
    protected $hidden = [];

    /**
     * @var string[] $with
     */
    protected $with = [];
}

<?php

namespace App\Models\Room;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Permission\Traits\HasRoles;

class RoomMember extends Model
{
    use HasFactory, HasRoles;

    protected $table = 'room_members';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'room_id',
        'user_id',
        'role',
    ];

    /**
     * @var string[]
     */
    protected $hidden = [];

    /**
     * @var string[]
     */
    protected $with = ['user'];

    /**
     * @var string[]
     */
    protected $appends = ['user_permissions'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getUserPermissionsAttribute(): array
    {
        return $this->user->getAllPermissions()->pluck('name')->toArray();
    }
}

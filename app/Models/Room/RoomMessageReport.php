<?php

namespace App\Models\Room;

use App\Models\ReportReason;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use MongoDB\Laravel\Eloquent\HybridRelations;

class RoomMessageReport extends Model
{
    use HasFactory, HybridRelations;

    /**
     * @var string $connection
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
        'message_id',
        'user_id',
        'description',
    ];

    /**
     * @var string[] $hidden
     */
    protected $hidden = [
        'pivot'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function reasons()
    {
        return $this->belongsToMany(ReportReason::class, 'room_message_report_reasons', 'room_message_report_id', 'report_reason_id');
    }

    /**
     * @return BelongsTo
     */
    public function message(): BelongsTo
    {
        return $this->belongsTo(RoomMessage::class);
    }
}

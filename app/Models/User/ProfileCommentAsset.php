<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use MongoDB\Laravel\Eloquent\Model;

class ProfileCommentAsset extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'profile_comment_id',
        'path',
    ];

    /**
     * @var string[]
     */
    protected $appends = [
        'formatted_path',
    ];

    public function comment(): BelongsTo
    {
        return $this->belongsTo(ProfileComment::class, 'profile_comment_id');
    }

    public function getFormattedPathAttribute(): string
    {
        $month = date('m', strtotime($this->created_at));
        $year = date('Y', strtotime($this->created_at));

        return "/audios/post-assets/{$year}/{$month}/{$this->path}";
    }

    public function createAudioFromData(UploadedFile $uploadedAudio): string
    {
        $currentMonth = date('n');
        $currentYear = date('Y');

        $folder = public_path('audios/post-assets/'.$currentYear.'/'.$currentMonth);

        if (! file_exists($folder)) {
            Storage::makeDirectory($folder);
        }

        $uploadedAudio->move($folder, $this->path);

        return $this->getFormattedPathAttribute();
    }
}

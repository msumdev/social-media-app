<?php

namespace App\Http\Resources\Chat;

use App\Models\Room\RoomMessage;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Redis;

class RoomResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'members' => $this->members->map(function ($member) {
                return [
                    'id' => $member->id,
                    'username' => $member->username,
                    'name' => $member->first_name,
                    'age' => $member->age,
                    'profile_picture' => $member->profile_picture->url,
                    'follower_count' => $member->follower_count,
                    'online' => Redis::get('user-count:' . $member->id) !== null,
                    'recipient' => $member->id != auth()->id(),
                    'permissions' => $member->getAllPermissions()->pluck('name'),
                ];
            }),
            'archive' => $this->archive,
            'type' => $this->type,
        ];
    }
}

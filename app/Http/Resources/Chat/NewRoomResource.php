<?php

namespace App\Http\Resources\Chat;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Redis;

class NewRoomResource extends JsonResource
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
            'members' => $this->members->map(function ($member) {
                return [
                    'id' => $member->user->id,
                    'username' => $member->user->username,
                    'name' => $member->user->first_name,
                    'age' => $member->user->age,
                    'profile_picture' => $member->user->profilePicture->url,
                    'follower_count' => $member->user->followers()->count(),
                    'online' => Redis::get('user-count:'.$member->user->id) !== null,
                ];
            }),
        ];
    }
}

<?php

namespace App\Http\Resources\Chat\Room;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Redis;

class GetRoomResource extends JsonResource
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

    /**
     * @param $member
     * @return mixed
     */
    public function isUserFriend($member): mixed
    {
        return auth()->user()
            ->friends
            ->where('id', $member->id)
            ->first();
    }

    /**
     * @param $member
     * @return string
     */
    public function getName($member): string
    {
        if ($this->isUserFriend($member)) {
            return $member->first_name . ' ' . $member->last_name;
        } else {
            return $member->username;
        }
    }
}

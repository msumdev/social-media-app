<?php

namespace App\Http\Resources\Chat;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Redis;
use Illuminate\Pagination\LengthAwarePaginator;

class RoomInfoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $roomId = $this->id;

        $moderators = $this->members
            ->filter(function ($member) use ($roomId) {
                $permissions = $member->getAllPermissions();

                return $permissions->contains('name', 'room.moderator.' . $roomId) &&
                    !$permissions->contains('name', 'room.owner.' . $roomId);
            })
            ->map(function ($member) {
                return [
                    'id' => $member->id,
                    'username' => $member->username,
                    'name' => $member->first_name,
                    'age' => $member->age,
                    'profile_picture' => $member->profile_picture->url,
                    'online' => Redis::get('user-count:' . $member->id) !== null,
                ];
            })
            ->values();

        $owners = $this->members
            ->filter(function ($member) use ($roomId) {
                $permissions = $member->getAllPermissions();

                return $permissions->contains('name', 'room.owner.' . $roomId);
            })
            ->map(function ($member) {
                return [
                    'id' => $member->id,
                    'username' => $member->username,
                    'name' => $member->first_name,
                    'age' => $member->age,
                    'profile_picture' => $member->profile_picture->url,
                    'online' => Redis::get('user-count:' . $member->id) !== null,
                ];
            })
            ->values();

        return [
            'name' => $this->name,
            'type' => $this->type,
            'member_count' => $this->members()->count(),
            'message_count' => $this->messages()->count(),
            'moderators' => $moderators,
            'owners' => $owners,
        ];
    }
}

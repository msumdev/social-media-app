<?php

namespace App\Http\Resources\Chat;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;

class ChatFriendListResource extends JsonResource
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
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'username' => $this->username,
            'age' => $this->age,
            'profile_picture' => $this->profile_picture->url,
            'status' => Cache::store('redis')->has('user:' . $this->id . ':session-count') ? 'online' : 'offline',
            'last_seen' => '2 hours ago'
        ];
    }
}

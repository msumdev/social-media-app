<?php

namespace App\Http\Resources\Chat\Room\Message;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoomMessageResource extends JsonResource
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
            'content' => $this->content,
            'room_id' => $this->room_id,
            'author' => [
                'id' => $this->user->id,
                'first_name' => $this->user->first_name,
                'username' => $this->user->username,
                'profile_picture' => $this->user->profile_picture->url,
            ],
            'created_at' => $this->created_at->diffForHumans(),
        ];
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "first_name" => $this->first_name,
            "last_name" => $this->last_name,
            "username" => $this->username,
            "country" => [
                "name" => $this->country->name,
                "code" => $this->country->code,
            ],
            "city" => [
                "name" => $this->city->name,
            ],
            "sexuality" => $this->sexuality->name,
            "sex" => [
                "name" => $this->sex->name,
                "code" => $this->sex->code
            ],
            "gender" => $this->gender->name,
            "age" => $this->age,
            "follower_count" => $this->follower_count,
            "profile_picture" => $this->profile_picture->url,
            "settings" => [
                "render_media" => $this->settings->render_media,
            ]
        ];
    }
}

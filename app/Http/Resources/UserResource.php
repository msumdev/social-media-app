<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

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
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'username' => $this->username,
            'country' => [
                'label' => $this->country->label,
                'value' => $this->country->value,
            ],
            'city' => [
                'label' => $this->city->label,
            ],
            'sexuality' => $this->sexuality->label,
            'sex' => [
                'label' => $this->sex->label,
                'value' => $this->sex->value,
            ],
            'gender' => $this->gender->label,
            'age' => $this->age,
            'follower_count' => $this->followers()->count(),
            'profile_picture' => $this->profilePicture->url,
            'settings' => [
                'render_media' => $this->userSetting->render_media,
            ],
        ];
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SearchCollectionResource extends JsonResource
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
            'username' => $this->name,
            'country' => [
                'name' => $this->country->name,
                'code' => $this->country->code,
            ],
            'city' => [
                'name' => $this->city->name,
            ],
            'sexuality' => $this->sexuality->name,
            'sex' => [
                'name' => $this->sex->name,
                'code' => $this->sex->code,
            ],
            'gender' => $this->gender->name,
            'age' => $this->age,
            'follower_count' => $this->followers()->count(),
            'profile_picture' => $this->profilePicture->url,
        ];
    }
}

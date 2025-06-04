<?php

namespace App\Http\Resources\Post;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostCollectionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->_id,
            'content' => $this->content,
            'mentions' => $this->mentions,
            'hashtags' => $this->hashtags,
            'comment_count' => $this->comment_count,
            'created_at_title' => $this->created_at_title,
            'created_at_display' => $this->created_at_display,
            'like_count' => $this->likes->count(),
            'liked_by_user' => $this->likes->contains('user_id', auth()->id()),
            'user' => [
                'id' => $this->user->id,
                'first_name' => $this->user->first_name,
                'last_name' => $this->user->last_name,
                'username' => $this->user->username,
                'country' => [
                    'label' => $this->user->country->label,
                    'value' => $this->user->country->value,
                ],
                'city' => [
                    'name' => $this->user->city->label,
                ],
                'sexuality' => $this->user->sexuality->label,
                'sex' => [
                    'label' => $this->user->sex->label,
                    'value' => $this->user->sex->value,
                ],
                'gender' => $this->user->gender->label,
                'age' => $this->user->age,
                'profile_picture' => $this->user->profilePicture->url,
            ],
            'image_assets' => $this->postImageAssets->map(function ($asset) {
                return [
                    'src' => $asset->url,
                ];
            })->toArray(),
            'audio_assets' => $this->postAudioAssets->map(function ($asset) {
                return [
                    'src' => $asset->url,
                ];
            })->toArray(),
        ];
    }
}

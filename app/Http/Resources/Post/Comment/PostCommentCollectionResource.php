<?php

namespace App\Http\Resources\Post\Comment;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;

class PostCommentCollectionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "_id" => $this->_id,
            "content" => $this->content,
            "mentions" => $this->mentions,
            "hashtags" => $this->hashtags,
            "comment_count" => $this->comment_count,
            "created_at_title" => $this->created_at_title,
            "created_at_display" => $this->created_at_display,
            "like_count" => $this->like_count,
            "user" => [
                "id" => $this->user->id,
                "first_name" => $this->user->first_name,
                "last_name" => $this->user->last_name,
                "username" => $this->user->username,
                "country" => [
                    "name" => $this->user->country->name,
                    "code" => $this->user->country->code,
                ],
                "city" => [
                    "name" => $this->user->city->name,
                ],
                "sexuality" => $this->user->sexuality->name,
                "sex" => [
                    "name" => $this->user->sex->name,
                    "code" => $this->user->sex->code
                ],
                "gender" => $this->user->gender->name,
                "age" => $this->user->age,
                "follower_count" => $this->user->follower_count,
                "profile_picture" => $this->user->profile_picture->url,
            ],
            'likes' => $this->likes->map(function ($like) {
                return [
                    "user" => [
                        "id" => $like->user->id,
                        "first_name" => $like->user->first_name,
                        "last_name" => $like->user->last_name,
                        "username" => $like->user->username,
                        "country" => [
                            "name" => $like->user->country->name,
                            "code" => $like->user->country->code,
                        ],
                        "city" => [
                            "name" => $like->user->city->name,
                        ],
                        "sexuality" => $like->user->sexuality->name,
                    ],
                ];
            })->toArray(),
            "assets" => $this->assets->map(function ($asset) {
                return [
                    "_id" => $asset->_id,
                    "url" => $asset->url,
                    "type" => $asset->type,
                ];
            })->toArray(),
        ];
    }
}

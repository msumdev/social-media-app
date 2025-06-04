<?php

namespace App\Http\Resources\Post\Comment;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostCommentResource extends JsonResource
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
            'mentions' => $this->mentions ?? [],
            'hashtags' => $this->hashtags ?? [],
            'created_at_title' => $this->created_at_title,
            'created_at_display' => $this->created_at_display,
            'reactions' => $this->postCommentReactions
                ->groupBy('reaction')
                ->map(function ($group) {
                    return [
                        'reaction' => $group->first()->reaction,
                        'reaction_unicode' => $group->first()->reaction_unicode,
                        'count' => $group->count(),
                        'user_ids' => $group->pluck('user_id')->toArray(),
                    ];
                })->values()->toArray(),
            'user' => [
                'id' => $this->user->id,
                'first_name' => $this->user->first_name,
                'last_name' => $this->user->last_name,
                'username' => $this->user->username,
                'profile_picture' => $this->user->profilePicture->url,
            ],
            'post_id' => $this->post->_id,
        ];
    }
}

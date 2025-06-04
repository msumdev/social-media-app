<?php

namespace App\Http\Resources\Post\Comment;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostCommentReactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'reaction' => $this->reaction,
            'reaction_unicode' => $this->reaction_unicode,
        ];
    }
}

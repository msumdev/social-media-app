<?php

namespace App\Services\User\Posts;

use App\Http\Requests\User\Posts\GetPostLikesRequest;
use App\Http\Resources\Post\Comment\PostCommentReactionResource;
use App\Models\Posts\PostCommentReaction;
use App\Models\Posts\PostLike;
use Illuminate\Http\JsonResponse;

/**
 * Class PostLikeService
 */
class PostCommentReactionService
{
    public function getLikes(GetPostLikesRequest $request): JsonResponse
    {
        $likes = PostLike::where('post_id', $request->id)->get();

        return response()->json($likes);
    }

    public function toggle(string $postCommentId, string $reaction, string $reactionUnicode): JsonResponse
    {
        $commentReaction = PostCommentReaction::where('post_comment_id', $postCommentId)
            ->where('user_id', auth()->id())
            ->where('reaction_unicode', $reactionUnicode)
            ->first();

        if (! $commentReaction) {
            PostCommentReaction::create([
                'post_comment_id' => $postCommentId,
                'user_id' => auth()->id(),
                'reaction' => $reaction,
                'reaction_unicode' => $reactionUnicode,
            ]);
        } else {
            $commentReaction->delete();
        }

        $reactions = PostCommentReaction::where('post_comment_id', $postCommentId)
            ->get()
            ->groupBy('reaction')
            ->map(function ($group) {
                return [
                    'reaction' => $group->first()->reaction,
                    'reaction_unicode' => $group->first()->reaction_unicode,
                    'count' => $group->count(),
                    'user_ids' => $group->pluck('user_id')->toArray(),
                ];
            })
            ->values();

        return response()->json($reactions);
    }
}

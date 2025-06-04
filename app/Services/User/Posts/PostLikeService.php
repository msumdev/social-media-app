<?php

namespace App\Services\User\Posts;

use App\Http\Requests\User\Posts\GetPostLikesRequest;
use App\Http\Resources\Post\PostLikeResource;
use App\Models\Posts\Post;
use App\Models\Posts\PostLike;
use Illuminate\Http\JsonResponse;

/**
 * Class PostLikeService
 */
class PostLikeService
{
    public function getLikes(GetPostLikesRequest $request): JsonResponse
    {
        $likes = PostLike::where('post_id', $request->id)->get();

        return response()->json($likes);
    }

    public function toggle(string $postId): PostLikeResource
    {
        $postLike = PostLike::where('post_id', $postId)
            ->where('user_id', auth()->id())
            ->first();

        if ($postLike) {
            $postLike->delete();
        } else {
            PostLike::create([
                'post_id' => $postId,
                'user_id' => auth()->id(),
            ]);
        }

        $post = Post::find($postId);

        return new PostLikeResource($post);

        //        return response()->json([
        //            'like_count' => $post->like_count,
        //            'liked_by_user' => $post->liked_by_user,
        //            'likes' => $post->likes,
        //        ]);
    }
}

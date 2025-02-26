<?php

namespace App\Services\User\Posts;

use App\Http\Requests\User\Posts\GetPostLikesRequest;
use App\Http\Requests\User\Posts\TogglePostLikeRequest;
use App\Models\Posts\Post;
use App\Models\Posts\PostLike;
use Illuminate\Http\JsonResponse;

/**
 * Class PostLikeService
 * @package App\Services\User\Posts
 */
class PostLikeService
{
    /**
     * @param GetPostLikesRequest $request
     * @return JsonResponse
     */
    public function getLikes(GetPostLikesRequest $request): JsonResponse
    {
        $likes = PostLike::where('post_id', $request->id)->get();

        return response()->json($likes);
    }

    /**
     * @param TogglePostLikeRequest $request
     * @return JsonResponse
     */
    public function toggle(TogglePostLikeRequest $request): JsonResponse
    {
        $postLike = PostLike::where('post_id', $request->id)
            ->where('user_id', auth()->id())
            ->get();

        if ($postLike->count() > 0) {
            $postLike = PostLike::where('post_id', $request->id)
                ->where('user_id', auth()->id())
                ->first();

            $postLike->delete();
        } else {
            PostLike::create([
                'post_id' => $request->id,
                'user_id' => auth()->id()
            ]);
        }

        $post = Post::where('_id', $request->id)->first();

        return response()->json([
            'like_count' => $post->like_count,
            'liked_by_user' => $post->liked_by_user,
            'likes' => $post->likes
        ]);
    }
}

<?php

namespace App\Services\User\Posts;

use App\Http\Requests\User\Posts\Comments\CreatePostCommentRequest;
use App\Http\Requests\User\Posts\Comments\DeletePostCommentRequest;
use App\Http\Requests\User\Posts\Comments\GetPostCommentRequest;
use App\Http\Resources\Post\Comment\PostCommentCollectionResource;
use App\Http\Resources\Post\Comment\PostCommentResource;
use App\Models\Asset;
use App\Models\Posts\PostComment;
use App\Models\Posts\PostCommentAsset;
use App\Models\Posts\PostDislike;
use App\Models\Posts\PostTest;
use App\Services\SanitizationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Class PostCommentService
 * @package App\Services\User\Posts
 */
class PostCommentService
{
    /**
     * @param GetPostCommentRequest $request
     * @return AnonymousResourceCollection
     */
    public function getPostComments(GetPostCommentRequest $request): AnonymousResourceCollection
    {
        $data = PostComment::where('post_id', $request->id)
            ->orderBy('created_at', 'desc')
            ->paginate(5);

        return PostCommentCollectionResource::collection($data);
    }

    /**
     * @param CreatePostCommentRequest $request
     * @return JsonResponse
     */
    public function create(CreatePostCommentRequest $request): JsonResponse
    {
        $sanitizationService = new SanitizationService();
        $content = $sanitizationService->sanitize($request->get('content'));

        $audios = $request->file('audios') ?? [];

        $comment = PostComment::create([
            'post_id' => $request->id,
            'user_id' => auth()->id(),
            'content' => $content,
            'mentions' => $request->get('mentions'),
            'hashtags' => $request->get('hashtags')
        ]);

        foreach ($audios as $audio) {
            $asset = Asset::create([
                'post_comment_id' => $comment->_id,
                'type' => Asset::POST_COMMENT_AUDIO,
                'path' => sha1(Str::random(16))
            ]);

            Storage::disk('post-comment-audios')->put($asset->path, $audio->get());
        }

        $comment = PostComment::where('_id', $comment->_id)->first();
        $commentCount = PostComment::where('post_id', $request->id)->count();

        return response()->json([
            'comment' => new PostCommentResource($comment),
            'comment_count' => $commentCount
        ]);
    }

    /**
     * @param DeletePostCommentRequest $request
     * @return JsonResponse
     */
    public function delete(DeletePostCommentRequest $request): JsonResponse
    {
        $comment = PostComment::where('_id', $request->comment_id)->first();
        $comment->delete();

        return response()->json([
            'message' => 'Comment deleted'
        ]);
    }
}

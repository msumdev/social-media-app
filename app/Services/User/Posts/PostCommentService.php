<?php

namespace App\Services\User\Posts;

use App\Http\Requests\User\Posts\Comments\CreatePostCommentRequest;
use App\Http\Requests\User\Posts\Comments\DeletePostCommentRequest;
use App\Http\Requests\User\Posts\Comments\GetPostCommentRequest;
use App\Http\Resources\Post\Comment\PostCommentCollectionResource;
use App\Http\Resources\Post\Comment\PostCommentResource;
use App\Models\Asset;
use App\Models\Posts\PostComment;
use App\Services\SanitizationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Class PostCommentService
 */
class PostCommentService
{
    public function getPostComments(GetPostCommentRequest $request): AnonymousResourceCollection
    {
        $data = PostComment::where('post_id', $request->id)
            ->with([
                'postCommentReactions',
                'user.profilePicture',
                'post',
            ])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return PostCommentCollectionResource::collection($data);
    }

    public function create(string $id, string $content, array $mentions, array $hashtags, array $audios): PostCommentResource
    {
        $sanitizationService = new SanitizationService;
        $content = $sanitizationService->sanitize($content);

        $comment = PostComment::create([
            'post_id' => $id,
            'user_id' => auth()->id(),
            'content' => $content,
            'mentions' => $mentions,
            'hashtags' => $hashtags,
        ]);

        foreach ($audios as $audio) {
            $asset = Asset::create([
                'post_comment_id' => $comment->_id,
                'type' => Asset::POST_COMMENT_AUDIO,
                'path' => sha1(Str::random(16)),
            ]);

            Storage::disk('post-comment-audio')->put($asset->path, $audio->get());
        }

        $comment = PostComment::where('_id', $comment->_id)->first();

        return new PostCommentResource($comment);
    }

    public function delete(DeletePostCommentRequest $request): JsonResponse
    {
        $comment = PostComment::where('_id', $request->comment_id)->first();
        $comment->delete();

        return response()->json([
            'message' => 'Comment deleted',
        ]);
    }
}

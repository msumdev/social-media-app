<?php

namespace App\Http\Controllers\User\Posts;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\Posts\Comments\CreatePostCommentReactionRequest;
use App\Http\Resources\Post\Comment\PostCommentReactionResource;
use App\Services\User\Posts\PostCommentReactionService;
use Illuminate\Http\JsonResponse;

class PostCommentReactionController extends Controller
{
    public function __construct(private readonly PostCommentReactionService $postCommentReactionService) {}

    public function toggle(CreatePostCommentReactionRequest $request): JsonResponse
    {
        $commentId = $request->comment_id;
        $reaction = $request->input('reaction');
        $reactionUnicode = $request->input('reaction_unicode');

        return $this->postCommentReactionService->toggle($commentId, $reaction, $reactionUnicode);
    }
}

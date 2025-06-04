<?php

namespace App\Http\Controllers\User\Posts;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\Posts\Comments\CreatePostCommentRequest;
use App\Http\Requests\User\Posts\Comments\DeletePostCommentRequest;
use App\Http\Requests\User\Posts\Comments\GetPostCommentRequest;
use App\Http\Resources\Post\Comment\PostCommentResource;
use App\Services\User\Posts\PostCommentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PostCommentController extends Controller
{
    public function __construct(private readonly PostCommentService $postCommentService) {}

    public function index(GetPostCommentRequest $request): AnonymousResourceCollection
    {
        return $this->postCommentService->getPostComments($request);
    }

    public function create(CreatePostCommentRequest $request): PostCommentResource
    {
        $id = $request->id;
        $mentions = $request->get('mentions') ?? [];
        $hashtags = $request->get('hashtags') ?? [];
        $audios = $request->file('audios') ?? [];
        $html = $request->get('html');

        return $this->postCommentService->create($id, $html, $mentions, $hashtags, $audios);
    }

    public function destroy(DeletePostCommentRequest $request): JsonResponse
    {
        return $this->postCommentService->delete($request);
    }
}

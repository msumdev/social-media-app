<?php

namespace App\Http\Controllers\User\Posts;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\Posts\Comments\CreatePostCommentRequest;
use App\Http\Requests\User\Posts\Comments\DeletePostCommentRequest;
use App\Http\Requests\User\Posts\Comments\GetPostCommentRequest;
use App\Services\User\Posts\PostCommentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PostCommentController extends Controller
{
    public function __construct(private readonly PostCommentService $postCommentService)
    {

    }

    /**
     * @param GetPostCommentRequest $request
     * @return AnonymousResourceCollection
     */
    public function index(GetPostCommentRequest $request): AnonymousResourceCollection
    {
        return $this->postCommentService->getPostComments($request);
    }

    /**
     * @param CreatePostCommentRequest $request
     * @return JsonResponse
     */
    public function create(CreatePostCommentRequest $request): JsonResponse
    {
        return $this->postCommentService->create($request);
    }

    /**
     * @param DeletePostCommentRequest $request
     * @return JsonResponse
     */
    public function destroy(DeletePostCommentRequest $request): JsonResponse
    {
        return $this->postCommentService->delete($request);
    }
}

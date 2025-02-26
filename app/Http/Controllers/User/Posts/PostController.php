<?php

namespace App\Http\Controllers\User\Posts;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\Posts\Comments\DeletePostCommentRequest;
use App\Http\Requests\User\Posts\Comments\GetPostCommentRequest;
use App\Http\Requests\User\Posts\Comments\CreatePostCommentRequest;
use App\Http\Requests\User\Posts\CreatePostRequest;
use App\Http\Requests\User\Posts\DeletePostRequest;
use App\Http\Requests\User\Posts\DislikeCommentRequest;
use App\Http\Requests\User\Posts\DislikePostRequest;
use App\Http\Requests\User\Posts\GetPostRequest;
use App\Http\Requests\User\Posts\GetPostsRequest;
use App\Http\Requests\User\Posts\LikeCommentRequest;
use App\Http\Requests\User\Posts\LikePostRequest;
use App\Http\Resources\Post\NewPostCollectionResource;
use App\Services\User\Posts\PostCommentService;
use App\Services\User\Posts\PostService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PostController extends Controller
{
    public function __construct(
        private readonly PostService $postService,
        private readonly PostCommentService $postCommentService
    )
    {

    }

    /**
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        $user = Auth::user()->load('friends', 'friend_requests');

        return view('posts.index', [
            'user' => $user
        ]);
    }

    /**
     * @param GetPostsRequest $request
     * @return JsonResponse
     */
    public function getPosts(GetPostsRequest $request): JsonResponse
    {
        return $this->postService->getPosts($request);
    }

    /**
     * @param GetPostRequest $request
     * @return JsonResponse
     */
    public function getPost(GetPostRequest $request): JsonResponse
    {
        return $this->postService->getPost($request);
    }

    /**
     * @param LikePostRequest $request
     * @return JsonResponse
     */
    public function likePost(LikePostRequest $request): JsonResponse
    {
        return $this->postService->likePost($request);
    }

    /**
     * @param DislikePostRequest $request
     * @return JsonResponse
     */
    public function dislikePost(DislikePostRequest $request): JsonResponse
    {
        return $this->postService->dislikePost($request);
    }

    /**
     * @param GetPostCommentRequest $request
     * @return JsonResponse
     */
    public function getPostComments(GetPostCommentRequest $request): JsonResponse
    {
        return $this->postCommentService->getPostComments($request);
    }

    /**
     * @param LikeCommentRequest $request
     * @return JsonResponse
     */
    public function likeComment(LikeCommentRequest $request): JsonResponse
    {
        return $this->postService->likeComment($request);
    }

    /**
     * @param DislikeCommentRequest $request
     * @return JsonResponse
     */
    public function dislikeComment(DislikeCommentRequest $request): JsonResponse
    {
        return $this->postService->dislikeComment($request);
    }

    /**
     * @param CreatePostRequest $request
     * @return NewPostCollectionResource
     */
    public function create(CreatePostRequest $request): NewPostCollectionResource
    {
        return $this->postService->create($request);
    }

    /**
     * @param DeletePostRequest $request
     * @return JsonResponse
     */
    public function delete(DeletePostRequest $request): JsonResponse
    {
        return $this->postService->delete($request);
    }

    /**
     * @param CreatePostCommentRequest $request
     * @return JsonResponse
     */
    public function createComment(CreatePostCommentRequest $request): JsonResponse
    {
        return $this->postService->createComment($request);
    }

    /**
     * @param DeletePostCommentRequest $request
     * @return JsonResponse
     */
    public function deleteComment(DeletePostCommentRequest $request): JsonResponse
    {
        return $this->postService->deleteComment($request);
    }
}

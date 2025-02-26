<?php

namespace App\Http\Controllers\User\Posts;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\Posts\GetPostLikesRequest;
use App\Http\Requests\User\Posts\TogglePostLikeRequest;
use App\Services\User\Posts\PostLikeService;
use Illuminate\Http\JsonResponse;

class PostLikeController extends Controller
{
    public function __construct(private readonly PostLikeService $postLikeService)
    {

    }

    /**
     * @param GetPostLikesRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(GetPostLikesRequest $request): JsonResponse
    {
        return $this->postLikeService->getLikes($request);
    }

    /**
     * @param TogglePostLikeRequest $request
     * @return JsonResponse
     */
    public function toggle(TogglePostLikeRequest $request): JsonResponse
    {
        return $this->postLikeService->toggle($request);
    }
}

<?php

namespace App\Http\Controllers\User\Posts;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\Posts\GetPostLikesRequest;
use App\Http\Requests\User\Posts\TogglePostLikeRequest;
use App\Http\Resources\Post\PostLikeResource;
use App\Services\User\Posts\PostLikeService;
use Illuminate\Http\JsonResponse;

class PostLikeController extends Controller
{
    public function __construct(private readonly PostLikeService $postLikeService) {}

    public function index(GetPostLikesRequest $request): JsonResponse
    {
        return $this->postLikeService->getLikes($request);
    }

    public function toggle(TogglePostLikeRequest $request): PostLikeResource
    {
        return $this->postLikeService->toggle($request->id);
    }
}

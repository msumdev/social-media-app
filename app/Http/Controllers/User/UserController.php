<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\BlockUserRequest;
use App\Http\Requests\User\GetUserListRequest;
use App\Http\Requests\User\GetUserRequest;
use App\Http\Resources\UserResource;
use App\Services\User\UserService;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    public function __construct(private readonly UserService $userService)
    {

    }

    /**
     * @param GetUserListRequest $request
     * @return JsonResponse
     */
    public function getUserList(GetUserListRequest $request): JsonResponse
    {
        return $this->userService->getUserList($request);
    }

    /**
     * @param GetUserRequest $request
     * @return UserResource
     */
    public function render(GetUserRequest $request): UserResource
    {
        return $this->userService->getUser($request);
    }

    /**
     * @param BlockUserRequest $request
     * @return JsonResponse
     */
    public function blockUser(BlockUserRequest $request): JsonResponse
    {
        return $this->userService->blockUser($request);
    }

    /**
     * @param GetUserRequest $request
     * @return JsonResponse
     */
    public function getUser(GetUserRequest $request): JsonResponse
    {
        return $this->userService->getUser($request);
    }
}

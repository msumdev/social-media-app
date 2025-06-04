<?php

namespace App\Http\Controllers\User\Comments;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\Comments\CreateUserProfileCommentRequest;
use App\Http\Requests\User\Comments\DeleteUserProfileCommentRequest;
use App\Http\Requests\User\Comments\GetUserProfileCommentRequest;
use App\Services\User\Comments\UserProfileCommentService;
use Illuminate\Http\JsonResponse;

/**
 * Class UserProfileCommentController
 */
class UserProfileCommentController extends Controller
{
    public function __construct(private readonly UserProfileCommentService $userProfileCommentService) {}

    /**
     * @return JsonResource
     */
    public function index(GetUserProfileCommentRequest $request): JsonResponse
    {
        return $this->userProfileCommentService->index($request);
    }

    public function create(CreateUserProfileCommentRequest $request): JsonResponse
    {
        return $this->userProfileCommentService->create($request);
    }

    public function destroy(DeleteUserProfileCommentRequest $request): JsonResponse
    {
        return $this->userProfileCommentService->destroy($request);
    }
}

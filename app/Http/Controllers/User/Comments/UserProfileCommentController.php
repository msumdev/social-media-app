<?php

namespace App\Http\Controllers\User\Comments;

use App\Facades\User\Comments\UserProfileCommentServiceFacade;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\Comments\CreateUserProfileCommentRequest;
use App\Http\Requests\User\Comments\DeleteUserProfileCommentRequest;
use App\Http\Requests\User\Comments\GetUserProfileCommentRequest;
use App\Services\User\Comments\UserProfileCommentService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

/**
 * Class UserProfileCommentController
 * @package App\Http\Controllers\User\Comments
 */
class UserProfileCommentController extends Controller
{
    public function __construct(private readonly UserProfileCommentService $userProfileCommentService)
    {

    }

    /**
     * @param GetUserProfileCommentRequest $request
     * @return JsonResource
     */
    public function index(GetUserProfileCommentRequest $request): JsonResponse
    {
        return $this->userProfileCommentService->index($request);
    }

    /**
     * @param CreateUserProfileCommentRequest $request
     * @return JsonResponse
     */
    public function create(CreateUserProfileCommentRequest $request): JsonResponse
    {
        return $this->userProfileCommentService->create($request);
    }

    /**
     * @param DeleteUserProfileCommentRequest $request
     * @return JsonResponse
     */
    public function destroy(DeleteUserProfileCommentRequest $request): JsonResponse
    {
        return $this->userProfileCommentService->destroy($request);
    }
}

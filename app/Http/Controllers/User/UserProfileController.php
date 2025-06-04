<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\Profile\GetUserProfileRequest;
use App\Services\User\Profile\UserProfileService;
use Illuminate\Http\JsonResponse;
use Inertia\Response;

class UserProfileController extends Controller
{
    public function __construct(private readonly UserProfileService $userProfileService) {}

    public function index(GetUserProfileRequest $request): Response|JsonResponse
    {
        return $this->userProfileService->index($request);
    }
}

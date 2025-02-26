<?php

namespace App\Services\User\Profile;

use App\Http\Requests\User\BlockUserRequest;
use App\Http\Requests\User\GetUserListRequest;
use App\Http\Requests\User\GetUserRequest;
use App\Http\Requests\User\Profile\GetUserProfileRequest;
use App\Models\AppLog;
use App\Models\User\BlockedUser;
use App\Models\User\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Inertia\Inertia;
use Inertia\Response;

/**
 * Class UserProfileService
 * @package App\Services\User\Profile
 */
class UserProfileService
{
    /**
     * @param GetUserProfileRequest $request
     * @return Response|JsonResponse
     */
    public function index(GetUserProfileRequest $request): Response|JsonResponse
    {
        $profile = User::where('username', $request->username)->first();
        $user = $request->user();

        if ($profile->id != $user->id) {
            AppLog::create([
                'user' => $user->id,
                'profile' => $profile->id,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'type' => AppLog::PROFILE_VIEW
            ]);
        }

        if ($request->get('json')) {
            return response()->json([
                'user' => $user,
                'profile' => $profile
            ]);
        }

        return Inertia::render('Profile/Index', [
            'username' => $request->username
        ]);
    }
}

<?php

namespace App\Services\User\Profile;

use App\Http\Requests\User\Profile\GetUserProfileRequest;
use App\Models\AppLog;
use App\Models\Session;
use App\Models\User\BlockedUser;
use App\Models\User\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

/**
 * Class UserProfileService
 */
class UserProfileService
{
    public function index(GetUserProfileRequest $request): RedirectResponse|Response|JsonResponse
    {
        $profile = User::where('username', $request->username)->first();

        if (! $profile) {
            Session::flash('error', 'Oh heck! I can\'t find this user!');

            return redirect()->route('home');
        }

        $currentUser = $request->user();
        $ipAddress = $request->ip();
        $userAgent = $request->userAgent();

        $this->logProfileVisit($currentUser, $profile, $ipAddress, $userAgent);

        if ($request->input('json')) {
            return response()->json([
                'user' => $currentUser,
                'profile' => $profile,
            ]);
        }

        return Inertia::render('Profile/Index', [
            'username' => $request->username,
        ]);
    }

    protected function logProfileVisit(User $viewed, User $viewer, string $ipAddress, string $userAgent): bool
    {
        if ($viewer->id === $viewed->id) {
            return false;
        }

        AppLog::create([
            'viewer_id' => $viewer->id,
            'viewed_id' => $viewed->id,
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
            'type' => AppLog::PROFILE_VIEW,
        ]);

        return true;
    }

    public function toggleBlockUser(User $user, User $blockedUser): void
    {
        if (! $user->hasBlocked($blockedUser)) {
            BlockedUser::create([
                'user_id' => $user->id,
                'blocked_user_id' => $blockedUser->id
            ]);
        }
    }
}

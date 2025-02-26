<?php

namespace App\Services\User;

use App\Http\Requests\User\BlockUserRequest;
use App\Http\Requests\User\GetUserListRequest;
use App\Http\Requests\User\GetUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User\BlockedUser;
use App\Models\User\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;

/**
 * Class UserService
 * @package App\Services\User
 */
class UserService
{
    /**
     * @param GetUserListRequest $request
     * @return JsonResponse
     */
    public function getUserList(GetUserListRequest $request): JsonResponse
    {
        $filters = $request->user()->filters;

        if (!$filters) {
            return response()->json([
                'success' => true,
                'data' => User::paginate(8),
            ]);
        }

        $users = User::query();

        if ($filters->sexes->count() > 0) {
            $users->whereIn('sex', json_decode($filters->sexes));
        }

        if ($filters->genders->count() > 0) {
            $users->whereIn('gender', json_decode($filters->genders));
        }

        if ($filters->countries->count() > 0) {
            $users->whereIn('country', json_decode($filters->countries));
        }

        if ($filters->city != null) {
            $users->where('city', json_decode($filters->cities));
        }

        if ($filters->keywords->count() > 0) {
            $users->where(function ($query) use ($filters) {
                foreach ($filters->keywords as $keyword) {
                    $query->orWhere('description', 'like', '%' . $keyword . '%');
                    $query->orWhere('status', 'like', '%' . $keyword . '%');
                }
            });
        }

        if ($filters->online) {
            $users->where('online', $filters->online);
        }

        $beforeYear = Carbon::now()->subYears($filters->age_from)->format('Y-m-d');
        $users->whereDate('date_of_birth', '<=', $beforeYear);

        $afterYear = Carbon::now()->subYears($filters->age_to)->format('Y-m-d');
        $users->whereDate('date_of_birth', '>=', $afterYear);

        return response()->json([
            'success' => true,
            'data' => $users->paginate(8),
        ]);
    }

    /**
     * @param GetUserRequest $request
     * @return UserResource
     */
    public function getUser(GetUserRequest $request): UserResource
    {
        return new UserResource($request->user());
    }

    /**
     * @param BlockUserRequest $request
     * @return JsonResponse
     */
    public function blockUser(BlockUserRequest $request): JsonResponse
    {
        $user = $request->user();
        $blockedUser = User::find($request->id);
        $existingBlockedUser = BlockedUser::where('user_id', $user->id)
            ->where('blocked_user_id', $blockedUser->id)
            ->first();

        if ($user->id == $blockedUser->id) {
            return response()->json([
                'success' => false,
                'message' => 'You can not block yourself',
            ]);
        }

        if ($existingBlockedUser) {
            return response()->json([
                'success' => false,
                'message' => 'User already blocked',
            ]);
        }

        $user->friends()->where('friend_id', $blockedUser->id)->delete();
        $blockedUser->friends()->where('friend_id', $user->id)->delete();

        $user->friend_requests()->where('user_id', $blockedUser->id)->delete();
        $blockedUser->friend_requests()->where('friend_id', $user->id)->delete();

        BlockedUser::create([
            'user_id' => $user->id,
            'blocked_user_id' => $blockedUser->id,
            'reason' => $request->reason,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'User blocked successfully',
        ]);
    }
}

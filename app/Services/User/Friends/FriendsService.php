<?php

namespace App\Services\User\Friends;

use App\Http\Requests\User\Friends\GetFriendsRequest;
use App\Http\Requests\User\SendFriendRequestRequest;
use App\Models\User\Friend;
use App\Models\User\FriendRequest;
use App\Models\User\User;
use App\Services\BaseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

/**
 * Class FriendsService
 */
class FriendsService extends BaseService
{
    public function getFriends(GetFriendsRequest $request): JsonResponse
    {
        $data = Auth::user()
            ->friends()
            ->with('friend:id,username,first_name,last_name,date_of_birth,country')
            ->get();

        $friends = $data->map(function ($friend) {
            return $friend->friend;
        });

        return $this->success('Friends retrieved successfully', $friends);
    }

    public function sendFriendRequest(SendFriendRequestRequest $request): JsonResponse
    {
        $user = $request->user();
        $friend = User::with([
            'friends',
            'friend_requests',
        ])->find($request->id);

        if (! $friend) {
            return $this->fail('User not found', null, 'info');
        }

        $existingFriend = Friend::where('user_id', $user->id)
            ->where('friend_id', $friend->id)
            ->first();

        if ($existingFriend) {
            return $this->fail('You are already friends', null, 'info');
        }

        $existingRequest = FriendRequest::where('user_id', $user->id)
            ->where('friend_id', $friend->id)
            ->first();

        if ($existingRequest) {
            return $this->fail('Friend request already sent', null, 'info');
        }

        FriendRequest::create([
            'user_id' => $user->id,
            'friend_id' => $friend->id,
        ]);

        return $this->success('Friend request sent', null, 'success');
    }
}

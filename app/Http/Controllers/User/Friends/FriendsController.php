<?php

namespace App\Http\Controllers\User\Friends;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\Friends\GetFriendsRequest;
use App\Http\Requests\User\SendFriendRequestRequest;
use App\Services\User\Friends\FriendsService;
use Illuminate\Http\JsonResponse;

class FriendsController extends Controller
{
    public function __construct(private readonly FriendsService $friendsService) {}

    /**
     * Show the messages page.
     */
    public function index(): \Illuminate\View\View
    {
        return view('friends.index');
    }

    public function getFriends(GetFriendsRequest $request): JsonResponse
    {
        return $this->friendsService->getFriends($request);
    }

    public function sendFriendRequest(SendFriendRequestRequest $request): JsonResponse
    {
        return $this->friendsService->sendFriendRequest($request);
    }
}

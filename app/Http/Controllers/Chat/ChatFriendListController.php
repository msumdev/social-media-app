<?php

namespace App\Http\Controllers\Chat;

use App\Http\Controllers\Controller;
use App\Http\Requests\Chat\Friends\ChatFriendListIndexRequest;
use App\Services\Chat\ChatFriendListService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ChatFriendListController extends Controller
{
    public function __construct(private readonly ChatFriendListService $chatFriendListService) {}

    public function index(ChatFriendListIndexRequest $request): AnonymousResourceCollection
    {
        return $this->chatFriendListService->index($request);
    }
}

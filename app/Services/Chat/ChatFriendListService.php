<?php

namespace App\Services\Chat;

use App\Http\Requests\Chat\Friends\ChatFriendListIndexRequest;
use App\Http\Requests\Chat\Friends\ChatFriendsIndexRequest;
use App\Http\Requests\Chat\Friends\ChatFriendsListIndexRequest;
use App\Http\Resources\Chat\ChatFriendListResource;
use App\Models\Room\Room;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use Inertia\Response;

/**
 * Class ChatFriendListService
 * @package App\Services\Chat
 */
class ChatFriendListService
{
    /**
     * @param ChatFriendListIndexRequest $request
     * @return AnonymousResourceCollection
     */
    public function index(ChatFriendListIndexRequest $request): AnonymousResourceCollection
    {
        $room = Room::find($request->room_id);
        $roomMembers = ($room) ? $room->members->pluck('user_id')->toArray() : [];

        $result = auth()->user()->friends()
            ->when($request->has('search'), function ($query) use ($request) {
                return $query->where(function ($q) use ($request) {
                    $q->where('users.first_name', 'like', '%' . $request->search . '%')
                        ->orWhere('users.last_name', 'like', '%' . $request->search . '%')
                        ->orWhere('users.username', 'like', '%' . $request->search . '%');
                });
            })
            ->when($request->has('exclude_existing'), function ($query) use ($roomMembers) {
                return $query->whereNotIn('users.id', $roomMembers);
            })
            ->paginate(25);

        return ChatFriendListResource::collection($result);
    }
}

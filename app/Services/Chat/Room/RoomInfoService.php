<?php

namespace App\Services\Chat\Room;

use App\Http\Requests\Chat\Rooms\GetChatRoomInfoRequest;
use App\Http\Resources\Chat\RoomInfoResource;
use App\Models\Room\Room;

/**
 * Class RoomInfoService
 */
class RoomInfoService
{
    public function index(GetChatRoomInfoRequest $request): RoomInfoResource
    {
        $room = Room::find($request->id);

        return new RoomInfoResource($room);
    }
}

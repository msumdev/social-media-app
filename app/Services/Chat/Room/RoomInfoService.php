<?php

namespace App\Services\Chat\Room;

use App\Http\Requests\Chat\Rooms\GetChatRoomInfoRequest;
use App\Http\Resources\Chat\RoomInfoResource;
use App\Models\Room\Room;

/**
 * Class RoomInfoService
 * @package App\Services\Chat\Room
 */
class RoomInfoService
{
    /**
     * @param GetChatRoomInfoRequest $request
     * @return RoomInfoResource
     */
    public function index(GetChatRoomInfoRequest $request): RoomInfoResource
    {
        $room = Room::find($request->id);

        return new RoomInfoResource($room);
    }
}

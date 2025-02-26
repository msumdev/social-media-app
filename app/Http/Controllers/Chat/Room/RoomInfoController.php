<?php

namespace App\Http\Controllers\Chat\Room;

use App\Http\Controllers\Controller;
use App\Http\Requests\Chat\Rooms\CreateChatRequest;
use App\Http\Requests\Chat\Rooms\GetChatRoomInfoRequest;
use App\Http\Resources\Chat\RoomInfoResource;
use App\Services\Chat\Room\RoomInfoService;

class RoomInfoController extends Controller
{
    public function __construct(private readonly RoomInfoService $chatRoomInfoService)
    {

    }

    /**
     * @param GetChatRoomInfoRequest $request
     * @return RoomInfoResource
     */
    public function index(GetChatRoomInfoRequest $request): RoomInfoResource
    {
        return $this->chatRoomInfoService->index($request);
    }
}

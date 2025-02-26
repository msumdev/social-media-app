<?php

namespace App\Http\Controllers\Chat\Room;

use App\Http\Requests\Chat\Rooms\GetChatRoomRequest;
use App\Http\Requests\Chat\Rooms\UpdateChatRoomRequest;
use App\Http\Resources\Chat\Room\GetRoomResource;
use App\Http\Resources\Chat\RoomResource;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Chat\Rooms\CreateChatRequest;
use App\Http\Requests\Chat\Rooms\CreateChatRoomRequest;
use App\Http\Requests\Chat\Rooms\DeleteChatRoomRequest;
use App\Http\Requests\Chat\Rooms\GetChatRoomsRequest;
use App\Http\Resources\Chat\NewRoomResource;
use App\Services\Chat\Room\RoomService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class RoomController extends Controller
{
    public function __construct(private readonly RoomService $chatRoomService)
    {

    }

    /**
     * @param GetChatRoomsRequest $request
     * @return AnonymousResourceCollection
     */
    public function index(GetChatRoomsRequest $request): AnonymousResourceCollection
    {
        $rooms = $this->chatRoomService->index($request->type ?? '');

        return GetRoomResource::collection($rooms);
    }

    /**
     * @param CreateChatRoomRequest $request
     * @return mixed
     */
    public function create(CreateChatRoomRequest $request): NewRoomResource
    {
        $members = $request->members ?? [];
        $user = auth()->user();
        $type = count($members) > 1 ? 'group' : 'direct';

        $room = $this->chatRoomService->create($type, $user, $members);

        return new NewRoomResource($room);
    }

    /**
     * @param UpdateChatRoomRequest $request
     * @return RoomResource
     */
    public function update(UpdateChatRoomRequest $request): RoomResource
    {
        $room = $this->chatRoomService->update(
            id: $request->id,
            name: $request->name,
            archive: $request->archive,
            purge: $request->purge,
            type: $request->type,
            users: $request->users
        );

        return new RoomResource($room);
    }

    /**
     * @param DeleteChatRoomRequest $request
     * @return JsonResponse
     */
    public function delete(DeleteChatRoomRequest $request): JsonResponse
    {
        return $this->chatRoomService->delete($request);
    }

    /**
     * @param GetChatRoomRequest $request
     * @return RoomResource
     */
    public function show(GetChatRoomRequest $request): RoomResource
    {
        return $this->chatRoomService->show($request);
    }
}

<?php

namespace App\Http\Controllers\Chat\Room;

use App\Http\Controllers\Controller;
use App\Http\Requests\Chat\Message\CreateMessageRequest;
use App\Http\Requests\Chat\Message\DeleteMessageRequest;
use App\Http\Requests\Chat\Message\GetMessageRequest;
use App\Http\Resources\Chat\Room\Message\RoomMessageResource;
use App\Services\Chat\Room\RoomMessageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 * Class ChatRoomMessageController
 */
class RoomMessageController extends Controller
{
    public function __construct(private readonly RoomMessageService $chatRoomMessageService) {}

    public function index(GetMessageRequest $request): AnonymousResourceCollection
    {
        return $this->chatRoomMessageService->index($request);
    }

    public function create(CreateMessageRequest $request): RoomMessageResource
    {
        $roomId = intval($request->id);
        $content = $request->get('content');
        $user = auth()->user();

        return $this->chatRoomMessageService->create(
            user: $user,
            roomId: $roomId,
            content: $content
        );
    }

    public function delete(DeleteMessageRequest $request): JsonResponse
    {
        return $this->chatRoomMessageService->delete($request);
    }
}

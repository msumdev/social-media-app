<?php

namespace App\Services\Chat\Room;

use App\Http\Requests\Chat\Message\DeleteMessageRequest;
use App\Http\Requests\Chat\Message\GetMessageRequest;
use App\Http\Resources\Chat\Room\Message\RoomMessageResource;
use App\Models\Room\Room;
use App\Models\Room\RoomMessage;
use App\Models\Room\RoomMessageCount;
use App\Models\User\User;
use App\Traits\RedisWebsocketTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Redis;

/**
 * Class RoomMessageService
 */
class RoomMessageService
{
    use RedisWebsocketTrait;

    public function index(GetMessageRequest $request): AnonymousResourceCollection
    {
        $roomId = intval($request->id);

        return RoomMessageResource::collection(
            RoomMessage::where('room_id', $roomId)
                ->orderBy('created_at', 'desc')
                ->paginate(50)
        );
    }

    public function create(User $user, int $roomId, string $content): RoomMessageResource
    {
        $message = RoomMessage::create([
            'room_id' => $roomId,
            'author_id' => $user->id,
            'content' => $content,
        ]);

        $resource = new RoomMessageResource($message);
        $usersToNotify = $this->getUserIdsFromRoom(
            $message->room_id,
            [
                $user->id,
            ]
        );
        $this->notify($usersToNotify, 'room:new-message', $resource);

        $messageCount = RoomMessageCount::where('room_id', $roomId)
            ->where('user_id', $user->id)
            ->first();

        if (! $messageCount) {
            RoomMessageCount::create([
                'room_id' => $roomId,
                'user_id' => $user->id,
                'count' => 1,
            ]);
        } else {
            $messageCount->increment('count');
        }

        $user->updateActivity();

        return $resource;
    }

    public function delete(DeleteMessageRequest $request): JsonResponse
    {
        $message = RoomMessage::where('id', $request->message_id)
            ->first();

        $usersToNotify = $this->getUserIdsFromRoom(
            $message->room_id,
            [
                auth()->id(),
            ]
        );

        $this->notify($usersToNotify, 'room:message-deleted', [
            'room_id' => $message->room_id,
            'message_id' => $message->id,
        ]);

        $message->delete();

        return response()->json(['message' => 'Message deleted'], 200);
    }

    public function notifyUserOfPurge(Room $room, RoomMessage $message, int $userId): void
    {
        $payload = json_encode([
            'type' => 'room:message-deleted',
            'userId' => $userId,
            'message' => [
                'room_id' => $room->id,
                'message_id' => $message->id,
            ],
        ]);

        Redis::publish('main_subscriber_channel', $payload);
    }
}

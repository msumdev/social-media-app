<?php

namespace App\Traits;

use App\Exceptions\Notifications\InvalidNotificationType;
use App\Models\Room\Room;
use Illuminate\Support\Facades\Redis;

trait RedisWebsocketTrait
{
    const array TYPES = ['room:new-message', 'room:message-deleted'];

    private function notify(array $usersToNotify, string $type, mixed $data): void
    {
        if (! in_array($type, self::TYPES)) {
            throw new InvalidNotificationType("Invalid notification type $type");
        }

        if (count($usersToNotify) == 0) {
            return;
        }

        Redis::pipeline(function ($pipe) use ($data, $usersToNotify, $type) {
            foreach ($usersToNotify as $userToNotify) {
                $payload = $this->createPayload($type, $data, $userToNotify);

                $pipe->publish('main_subscriber_channel', $payload);
            }
        });
    }

    protected function createPayload(string $type, mixed $data, int $userToNotify): string
    {
        $payload = [
            'type' => $type,
            'data' => $data,
            'userId' => $userToNotify,
        ];

        return json_encode($payload);
    }

    public function getUserIdsFromRoom(int $roomId, array $exclude = []): array
    {
        $room = Room::find($roomId);
        $members = $room->members->pluck('id')->toArray();

        return array_diff($members, $exclude);
    }
}

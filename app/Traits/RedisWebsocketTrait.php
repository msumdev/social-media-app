<?php

namespace App\Traits;

use Illuminate\Support\Facades\Redis;
use App\Models\Room\Room;

trait RedisWebsocketTrait
{

    /**
     * @param array $usersToNotify
     * @param string $type
     * @param $data
     * @return void
     */
    private function notify(array $usersToNotify, string $type, $data): void
    {
        if (count($usersToNotify) == 0) {
            return;
        }

        Redis::pipeline(function ($pipe) use ($data, $usersToNotify, $type) {
            $payload = [
                'type' => $type,
                'data' => $data
            ];

            foreach ($usersToNotify as $userToNotify) {
                $payload['userId'] = $userToNotify;

                $pipe->publish('main_subscriber_channel', json_encode($payload));
            }
        });
    }

    /**
     * @param int $roomId
     * @param array $exclude
     * @return array
     */
    public function getUsersToNotify(int $roomId, array $exclude = []): array
    {
        $room = Room::find($roomId);
        $members = $room->members->pluck('user_id')->toArray();

        if ($room->recipient_id && !in_array($room->recipient_id, $members)) {
            $members[] = $room->recipient_id;
        }

        return array_diff($members, $exclude);
    }
}

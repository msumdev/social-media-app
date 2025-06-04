<?php

namespace App\Services\Chat\Room;

use App\Http\Requests\Chat\Rooms\DeleteChatRoomRequest;
use App\Http\Requests\Chat\Rooms\GetChatRoomRequest;
use App\Http\Resources\Chat\RoomResource;
use App\Models\Room\Room;
use App\Models\Room\RoomMember;
use App\Models\User\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Redis;
use Spatie\Permission\Models\Permission;

/**
 * Class RoomService
 */
class RoomService
{
    public function index(string $type): LengthAwarePaginator
    {
        return Room::when($type, function ($query) use ($type) {
            switch ($type) {
                case 'direct':
                    $query->where('type', 'direct');
                    break;
                case 'group':
                    $query->where('type', 'group');
                    break;
                case 'archived':
                    $query->where('archive', true);
                    break;
            }
        })
            ->where(function ($query) {
                $query->whereHas('members', function ($query) {
                    $query->where('room_members.user_id', auth()->id());
                });
            })
            ->paginate(20);
    }

    public function create(string $type, User $user, array $members): Room
    {
        $room = Room::create([
            'name' => ($type === 'group') ? 'New Group' : null,
            'type' => $type,
            'archive' => false,
        ]);

        Permission::create(['name' => 'room.owner.'.$room->id]);
        $user->givePermissionTo('room.owner.'.$room->id);

        RoomMember::create([
            'room_id' => $room->id,
            'user_id' => $user->id,
        ]);

        foreach ($members as $member) {
            RoomMember::create([
                'room_id' => $room->id,
                'user_id' => $member,
            ]);
        }

        return $room;
    }

    public function update(int $id, string $name, bool $archive, bool $purge, string $type, array $users = []): Room
    {
        $room = Room::find($id);

        if (! empty($name)) {
            $room->name = $name;
        }

        if ($archive) {
            $room->archive = $archive;
        }

        if ($purge) {
            $room->messages()->delete();
            $members = $room->members->pluck('id')->toArray();

            foreach ($members as $member) {
                $this->notifyUserOfPurge($room, $member);
            }
        }

        $members = $room->members->pluck('user_id')->toArray();

        switch ($type) {
            case 'add':
                $users = array_diff($users, $members);

                foreach ($users as $user) {
                    RoomMember::create([
                        'room_id' => $room->id,
                        'user_id' => $user,
                    ]);
                }
                break;

            case 'remove':
                $users = array_intersect($users, $members);

                foreach ($users as $user) {
                    RoomMember::where('room_id', $room->id)
                        ->where('user_id', $user)
                        ->delete();
                }
                break;
        }

        $room->save();

        return $room;
    }

    public function notifyUserOfPurge(Room $room, int $userId): void
    {
        $payload = json_encode([
            'type' => 'room:purge',
            'userId' => $userId,
            'message' => [
                'room_id' => $room->id,
            ],
        ]);

        Redis::publish('main_subscriber_channel', $payload);
    }

    public function delete(DeleteChatRoomRequest $request): JsonResponse
    {
        $room = Room::find($request->id);
        $room->delete();

        return response()->json([
            'message' => 'Room deleted',
        ]);
    }

    public function show(GetChatRoomRequest $request): RoomResource
    {
        $room = Room::find($request->id);

        return new RoomResource($room);
    }
}

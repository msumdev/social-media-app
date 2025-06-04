<?php

namespace Tests\Integration;

use App\Exceptions\Notifications\InvalidNotificationType;
use App\Models\Room\Room;
use App\Models\User\User;
use App\Traits\RedisWebsocketTrait;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Redis;
use Mockery;
use Tests\TestCase;

class RedisNotificationTest extends TestCase
{
    use DatabaseMigrations;
    use RedisWebsocketTrait;

    public function test_websocket_payload_is_created_correctly(): void
    {
        $payload = $this->createPayload('notify', ['message' => 'Hello world'], $this->user->id);

        $this->assertJson($payload);
    }

    public function test_exception_thrown_if_invalid_type_provided(): void
    {
        $memberCount = 5;
        $room = Room::factory()->create();
        $members = User::factory()->count($memberCount)->create();

        $room->members()->attach($members);

        $userIds = $this->getUserIdsFromRoom($room->id);

        $this->expectException(InvalidNotificationType::class);

        $this->notify($userIds, 'some:type', ['message' => 'some message']);
    }

    public function test_notifications_are_sent_if_valid_data_provided(): void
    {
        Redis::spy();

        $memberCount = 5;
        $room = Room::factory()->create();
        $members = User::factory()->count($memberCount)->create();

        $room->members()->attach($members);

        $this->assertEquals($memberCount, $room->members()->count());

        $userIds = $this->getUserIdsFromRoom($room->id);

        $this->notify($userIds, 'room:new-message', ['message' => 'Hello world']);

        Redis::shouldHaveReceived('pipeline')
            ->once()
            ->with(Mockery::on(function ($callback) {
                $pipe = Mockery::mock();
                $pipe->shouldReceive('publish')
                    ->with('main_subscriber_channel', Mockery::any())
                    ->times(5);

                $callback($pipe);

                return true;
            }));
    }

    public function test_notifications_are_not_sent_if_there_are_no_users(): void
    {
        Redis::spy();

        $room = Room::factory()->create();

        $userIds = $this->getUserIdsFromRoom($room->id);

        $this->notify($userIds, 'room:new-message', ['message' => 'Hello world']);

        Redis::shouldNotReceive('pipeline');
    }

    public function test_users_are_retrieved_correctly_from_room(): void
    {
        $memberCount = 5;
        $room = Room::factory()->create();
        $members = User::factory()->count($memberCount)->create();

        $room->members()->attach($members);

        $this->assertEquals($memberCount, $room->members()->count());

        $retrievedUserIds = $this->getUserIdsFromRoom($room->id);
        $validIds = $room->members->pluck('id')->toArray();

        $this->assertEquals($retrievedUserIds, $validIds);

        $toExclude = $room->members()->inRandomOrder()->first()->id;

        $retrievedUserIds = $this->getUserIdsFromRoom($room->id, [$toExclude]);

        $this->assertNotEquals($retrievedUserIds, $validIds);
        $this->assertNotContains($toExclude, $retrievedUserIds);
    }
}

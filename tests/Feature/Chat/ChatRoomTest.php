<?php


namespace Chat;

use App\Models\Room\Room;
use App\Models\User\Friend;
use App\Models\User\User;
use App\Services\Chat\Room\RoomService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChatRoomTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthenticated_user_cannot_load_chat(): void
    {
        $response = $this->get('/chat');

        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    public function test_authenticated_user_can_load_chat(): void
    {
        $this->actingAs($this->user);
        $response = $this->get('/chat');

        $response->assertStatus(200);
    }

    public function test_room_listing_for_direct_chats(): void
    {
        $this->actingAs($this->user);

        $rooms = Room::factory()->count(5)->create([
            'type' => 'direct'
        ]);

        $rooms->each(function ($room) {
            $room->members()->attach($this->user);
        });

        $response = $this->get('/chat/room');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'type',
                    'archive',
                    'members'
                ]
            ]
        ]);

        $response->assertJsonCount(5, 'data');
    }

    public function test_room_listing_for_group_chats(): void
    {
        $this->actingAs($this->user);

        $rooms = Room::factory()->count(5)->create([
            'type' => 'group'
        ]);

        $rooms->each(function ($room) {
            $room->members()->attach($this->user);
        });

        $response = $this->get('/chat/room?groups=1');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'type',
                    'archive',
                    'members'
                ]
            ]
        ]);

        $response->assertJsonCount(5, 'data');
    }

    public function test_room_listing_for_archived_chats(): void
    {
        $this->actingAs($this->user);

        $roomService = new RoomService();
        $room = $roomService->create('direct', $this->user, []);
        $room->archive = true;
        $room->save();

        $response = $this->get('/chat/room?archived=1');

        $response->assertStatus(200);
        $response->assertJsonCount(1, 'data');
    }
}

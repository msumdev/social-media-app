<?php

namespace Chat;

use App\Models\User\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class FriendsListTest extends TestCase
{
    use DatabaseMigrations;

    public function test_friends_list_route_returns_a_paginated_list_of_a_users_friends(): void
    {
        $this->actingAs($this->user);

        $this->user->friends()->attach(
            User::factory()
                ->count(5)
                ->create()
                ->pluck('id')
        );

        $response = $this->get('/chat/friends');

        $response->assertStatus(200);
        $response->assertJsonCount(5, 'data');
    }
}

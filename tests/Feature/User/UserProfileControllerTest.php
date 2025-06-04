<?php

namespace Tests\Feature;

use App\Models\AppLog;
use App\Models\User\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class UserProfileControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function test_user_cannot_access_user_profile_if_not_logged_in(): void
    {
        $response = $this->get('/u/'.$this->user->username);

        $response->assertStatus(302);

        $response->assertRedirect('/login');
    }

    public function test_user_can_access_user_profile_if_logged_in(): void
    {
        $this->actingAs($this->user);

        $response = $this->get('/u/'.$this->user->username);

        $response
            ->assertStatus(200);

        $user = User::factory()->create();

        $logs = AppLog::all();

        $this->assertEquals(0, $logs->count());

        $this->actingAs($user);

        $response = $this->get('/u/'.$this->user->username);

        $logs = AppLog::all();

        $this->assertEquals(1, $logs->count());
    }
}

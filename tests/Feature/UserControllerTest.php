<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function test_user_endpoint_is_not_accessible_without_authentication(): void
    {
        $response = $this->get('/user');

        $response
            ->assertStatus(302)
            ->assertRedirect('/login');
    }

    public function test_user_endpoint_is_accessible_after_authentication(): void
    {
        $this->actingAs($this->user);

        $response = $this->get('/user');

        $response->assertStatus(200);
    }

    public function test_user_information_is_logged_in_user_info(): void
    {
        $this->actingAs($this->user);

        $response = $this->get('/user');

        $response->assertJsonFragment(['id' => $this->user->id]);
    }
}

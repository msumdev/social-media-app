<?php

namespace Tests\Feature;

use App\Models\User\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class SearchControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function test_user_cannot_access_search_endpoint_if_not_logged_in(): void
    {
        $response = $this->get(route('search.index'));

        $response->assertStatus(302);

        $response->assertRedirect('/login');
    }

    public function test_user_can_access_search_endpoint_while_being_logged_in(): void
    {
        $this->actingAs($this->user);

        $response = $this->get(route('search.index'));

        $response->assertStatus(200)
            ->assertJsonFragment([
                'id' => $this->user->id,
            ]);

        User::factory()->create();

        $response = $this->get(route('search.index'));
        $userCount = count($response->json()['data']);

        $this->assertEquals(2, $userCount);
    }
}

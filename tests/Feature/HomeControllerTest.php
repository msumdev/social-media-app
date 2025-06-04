<?php

namespace Tests\Feature;

use App\Models\User\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class HomeControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function test_the_application_is_not_accessible_to_unathenticated_users(): void
    {
        $response = $this->get('/');

        $response->assertStatus(302);

        $response->assertRedirect('/login');
    }

    public function test_authenticated_users_can_access_home_page(): void
    {
        $this->actingAs($this->user)->get('/')
            ->assertInertia(
                fn (Assert $page) => $page->component('Home')
            );
    }

    public function test_authenticated_but_unverified_users_are_redirected_to_login_page(): void
    {
        $unverifiedUser = User::factory()->create([
            'registered' => false,
        ]);

        $response = $this->actingAs($unverifiedUser)
            ->get('/');

        $response->assertRedirect('/login');
    }

    public function test_banned_users_are_redirected_to_login_page(): void
    {
        $user = User::factory()->create();

        $this->assertFalse($user->banned);

        $user->banned = true;
        $user->save();

        $this->assertTrue($user->banned);

        $response = $this->actingAs($user)
            ->get('/');

        $response
            ->assertStatus(302)
            ->assertRedirect('/login');
    }
}

<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class LoginPageTest extends TestCase
{
    use DatabaseMigrations;

    public function test_user_can_access_login_page(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }
}

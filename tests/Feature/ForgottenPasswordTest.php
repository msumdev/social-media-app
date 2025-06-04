<?php

namespace Tests\Feature;

use App\Jobs\Auth\ResetPassword;
use App\Models\User\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class ForgottenPasswordTest extends TestCase
{
    use DatabaseMigrations;

    public function test_user_can_reset_their_password(): void
    {
        Queue::fake();

        $user = User::factory()->create([
            'date_of_birth' => Carbon::create(1996, 8, 7),
            'email' => 'example@test.com',
        ]);

        $this->post('/forgotten-password', [
            'date_of_birth' => '1996-08-07',
            'email' => 'example@test.com',
        ]);

        Queue::assertPushed(ResetPassword::class, 1);
    }

    public function test_user_date_of_birth_validation(): void
    {
        Queue::fake();

        User::factory()->create([
            'date_of_birth' => Carbon::create(1996, 8, 7),
            'email' => 'example@test.com',
        ]);

        $this->post('/forgotten-password', [
            'date_of_birth' => '1996-08-06',
            'email' => 'example@test.com',
        ]);

        Queue::assertNotPushed(ResetPassword::class, 1);
    }
}

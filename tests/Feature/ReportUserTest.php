<?php

namespace Tests\Feature;

use App\Models\User\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class ReportUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_report_user(): void
    {
        $user = User::factory()->create();
        $reason = Str::random(32);

        $response = $this->actingAs($this->user)
            ->post("/u/{$user->id}/report", [
                'reason' => $reason,
            ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('user_reports', [
            'user_id' => $user->id,
            'reason' => $reason,
            'reporter_id' => $this->user->id,
        ]);
    }

    public function test_logged_out_user_cannot_report_user(): void
    {
        $user = User::factory()->create();
        $reason = Str::random(32);

        $response = $this->post("/u/{$user->id}/report", [
                'reason' => $reason,
            ])
            ->assertStatus(302);

        $this->assertDatabaseMissing('user_reports', [
            'user_id' => $user->id,
            'reason' => $reason,
        ]);

        $response->assertRedirect('/login');
    }

    public function test_user_cannot_report_themselves(): void
    {
        $reason = Str::random(32);

        $response = $this->actingAs($this->user)
            ->post("/u/{$this->user->id}/report", [
                'reason' => $reason,
            ])
            ->assertStatus(400);

        $this->assertDatabaseMissing('user_reports', [
            'user_id' => $this->user->id,
            'reason' => $reason
        ]);

        $response->assertJson([
            'message' => 'You cannot report yourself',
        ]);
    }

    public function test_user_without_permission_cannot_view_reports(): void
    {
        $response = $this->actingAs($this->user)
            ->get("/reports")
            ->assertStatus(403);
    }

    public function test_user_can_get_report_reasons(): void
    {
        $response = $this->actingAs($this->user)
            ->get("/report-reasons")
            ->assertStatus(200);

        $this->assertIsArray($response->json());
    }
}

<?php

namespace Tests\Feature;

use App\Models\User\User;
use App\Models\User\UserReport;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Str;
use Tests\TestCase;

class ReportControllerTest extends TestCase
{
    use DatabaseMigrations;

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
        ]);

        $response
            ->assertStatus(302)
            ->assertRedirect('/login');

        $this->assertDatabaseMissing('user_reports', [
            'user_id' => $user->id,
            'reason' => $reason,
        ]);
    }

    public function test_user_cannot_report_themselves(): void
    {
        $reason = Str::random(32);

        $response = $this->actingAs($this->user)
            ->post("/u/{$this->user->id}/report", [
                'reason' => $reason,
            ]);

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors(['id']);

        $this->assertDatabaseMissing('user_reports', [
            'user_id' => $this->user->id,
            'reason' => $reason,
        ]);
    }

    public function test_user_without_permission_cannot_view_reports(): void
    {
        $response = $this->actingAs($this->user)
            ->get('/reports');

        $response->assertStatus(403);
    }

    public function test_user_with_permission_can_view_reports(): void
    {
        UserReport::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $this->user->assignRole('admin');

        $response = $this->actingAs($this->user)
            ->get('/reports');

        $response->assertStatus(200);
    }

    public function test_user_can_get_report_reasons(): void
    {
        $response = $this->actingAs($this->user)
            ->get('/report-reasons');

        $response->assertStatus(200);

        $this->assertIsArray($response->json());
    }
}

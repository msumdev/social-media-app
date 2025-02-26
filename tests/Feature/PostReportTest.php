<?php


use App\Models\Posts\Post;
use App\Models\ReportReason;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User\User;
use Tests\TestCase;

class PostReportTest extends TestCase
{
    use RefreshDatabase;

    public function test_verify_unauthenticated_user_cannot_report_a_post(): void
    {
        $reasons = ReportReason::inRandomOrder()->take(3)->pluck('id')->toArray();
        $description = fake()->sentence();

        $response = $this->post('/post/1/report', [
            'reasons' => $reasons,
            'description' => $description,
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/login');

        $this->assertDatabaseMissing('post_reports', [
            'post_id' => 1,
            'user_id' => $this->user->id,
            'reasons' => json_encode($reasons),
            'description' => $description,
        ]);
    }

    public function test_a_verified_user_can_report_a_users_post(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);
        $reasons = ReportReason::factory()->count(3)->create()->pluck('id')->toArray();
        $description = fake()->sentence();

        $response = $this->actingAs($this->user)
            ->post("/post/{$post->id}/report", [
                'reasons' => $reasons,
                'description' => $description,
            ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('post_reports', [
            'post_id' => $post->id,
            'user_id' => $this->user->id,
            'description' => $description,
        ]);
    }

    public function test_a_verified_user_cannot_report_their_own_post(): void
    {
        $post = Post::factory()->create(['user_id' => $this->user->id]);
        $reasons = ReportReason::factory()->count(3)->create()->pluck('id')->toArray();
        $description = fake()->sentence();

        $response = $this->actingAs($this->user)
            ->post("/post/{$post->id}/report", [
                'reasons' => $reasons,
                'description' => $description,
            ]);

        $response->assertStatus(400);

        $response->assertJsonPath('message', 'You cannot report your own post');

        $this->assertDatabaseMissing('post_reports', [
            'post_id' => $post->id,
            'user_id' => $this->user->id,
            'description' => $description,
        ]);
    }

    public function test_a_user_cannot_report_the_same_post_more_than_once(): void
    {$user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);
        $reasons = ReportReason::factory()->count(3)->create()->pluck('id')->toArray();
        $description = fake()->sentence();

        $response = $this->actingAs($this->user)
            ->post("/post/{$post->id}/report", [
                'reasons' => $reasons,
                'description' => $description,
            ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('post_reports', [
            'post_id' => $post->id,
            'user_id' => $this->user->id,
            'description' => $description,
        ]);

        $response = $this->actingAs($this->user)
            ->post("/post/{$post->id}/report", [
                'reasons' => $reasons,
                'description' => $description,
            ]);

        $response->assertStatus(400);

        $response->assertJsonPath('message', 'You have already reported this post');
    }
}

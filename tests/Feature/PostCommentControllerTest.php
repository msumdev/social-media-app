<?php

namespace Tests\Feature;

use App\Models\Posts\Post;
use App\Models\Posts\PostComment;
use App\Models\User\User;
use App\Services\User\Profile\UserProfileService;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class PostCommentControllerTest extends TestCase
{
    use DatabaseMigrations;

    private UserProfileService $userProfileService;

    public function setUp(): void
    {
        parent::setUp();

        $this->userProfileService = new UserProfileService();
    }

    public function test_user_can_load_comments_for_post(): void
    {
        $exampleUser = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $exampleUser->id]);

        $response = $this->actingas($this->user)->get("/post/{$post->id}/comments");
        $response->assertJsonCount(1, 'data');
    }

    public function test_user_does_not_get_comments_for_blocked_user(): void
    {
        $exampleUser = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $exampleUser->id]);

        $this->userProfileService->toggleBlockUser($exampleUser, $post);

        $response = $this->actingas($this->user)->get("/post/{$post->id}/comments");
        dd($response->json());
        $response->assertJsonCount(0, 'data');
    }
}

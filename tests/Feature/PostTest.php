<?php

namespace Tests\Feature;

use App\Models\Posts\Post;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Str;
use Tests\TestCase;

class PostTest extends TestCase
{
    use DatabaseMigrations;

    public function test_user_can_create_post(): void
    {
        $mention = Str::random(10);
        $hashtag = Str::random(10);
        $string = '<p>Hi there,</p><p><br /></p><p>How are you?</p><p><br /></p><p>I enjoyed speaking to <span class="badge mention">@jake</span></p><p><br /></p><p><span class="badge hashtag">#newfriends</span></p>';

        $response = $this->actingAs($this->user)
            ->post(
                route('post.create'),
                [
                    'content' => $string,
                    'mentions' => [$mention],
                    'hashtags' => [$hashtag],
                ]
            );

        $response->assertStatus(302);
        $this->assertDatabaseHas(Post::class, [
            'content' => $string,
            'mentions' => [$mention],
            'hashtags' => [$hashtag],
        ]);
    }

    public function test_a_user_must_be_logged_in_to_make_a_post(): void
    {
        $mention = Str::random(10);
        $hashtag = Str::random(10);
        $string = '<p>Hi there,</p><p><br /></p><p>How are you?</p><p><br /></p><p>I enjoyed speaking to <span class="badge mention">@jake</span></p><p><br /></p><p><span class="badge hashtag">#newfriends</span></p>';

        $response = $this->post(
            route('post.create'),
            [
                'content' => $string,
                'mentions' => [$mention],
                'hashtags' => [$hashtag],
            ]
        );

        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }
}

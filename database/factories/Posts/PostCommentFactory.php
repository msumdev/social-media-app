<?php

namespace Database\Factories\Posts;

use App\Models\Posts\Post;
use App\Models\Posts\PostComment;
use App\Models\Posts\PostCommentAudioAsset;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\File;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User\User>
 */
class PostCommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $sentences = File::json(resource_path('example_data/sentences.json'), true)['sentences'];
        $hashtags = File::json(resource_path('example_data/hashtags.json'), true)['hashtags'];

        return [
            'content' => $this->faker->randomElement($sentences),
            'hashtags' => $this->faker->randomElements($hashtags, rand(1, 6)),
        ];
    }

    /**
     * Configure any relationships while creating the post
     */
    public function configure(): static
    {
        return $this->afterCreating(function (PostComment $postComment) {
            $postComment->postCommentAudioAssets()->saveMany(
                PostCommentAudioAsset::factory()
                    ->count(rand(0, 2))
                    ->create([
                        'post_comment_id' => $postComment->id,
                    ])
            );
        });
    }
}

<?php

namespace Database\Factories\Posts;

use App\Models\Posts\Post;
use App\Models\Posts\PostAudioAsset;
use App\Models\Posts\PostComment;
use App\Models\Posts\PostImageAsset;
use App\Models\Posts\PostLike;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\File;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Posts\Post>
 */
class PostFactory extends Factory
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
            'user_id' => User::factory(),
        ];
    }

    /**
     * Configure any relationships while creating the post
     */
    public function configure(): static
    {
        return $this->afterCreating(function (Post $post) {
            $post->comments()->saveMany(
                PostComment::factory()
                    ->count(20)
                    ->create([
                        'post_id' => $post->id,
                        'user_id' => $post->user->id,
                    ])
            );

            $post->likes()->save(
                PostLike::factory()
                    ->create([
                        'post_id' => $post->id,
                    ])
            );

            $post->postAudioAssets()->save(
                PostAudioAsset::factory()
                    ->create([
                        'post_id' => $post->id,
                    ])
            );

            $post->postImageAssets()->save(
                PostImageAsset::factory()
                    ->create([
                        'post_id' => $post->id,
                    ])
            );
        });
    }
}

<?php

namespace Database\Factories\Posts;

use App\Models\Posts\Post;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

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
        $sentences = File::json(database_path('data/sentences.json'), true)["sentences"];

        return [
            '_id' => Str::random(24),
            'content' => $this->faker->randomElement($sentences),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}

<?php

namespace Database\Factories\User;

use App\Models\User\ProfileComment;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User\User>
 */
class ProfileCommentLikeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user' => User::inRandomOrder()->first()->id,
            'profile_comment_id' => ProfileComment::first()->id,
        ];
    }
}

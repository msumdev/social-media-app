<?php

namespace Database\Factories\User;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\File;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User\User>
 */
class ProfileCommentFactory extends Factory
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
            'user' => User::inRandomOrder()->first()->id,
            'content' => $this->faker->randomElement($sentences),
        ];
    }
}

<?php

namespace Database\Factories\User;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User\User>
 */
class UserProfileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'description' => $this->faker->text,
            'status' => $this->faker->text,
            'background_colour' => $this->faker->safeHexColor(),
            'background_image' => '',
            'user_info_background_colour' => $this->faker->safeHexColor(),
            'user_info_text_colour' => $this->faker->safeHexColor(),
            'about_me_background_colour' => $this->faker->safeHexColor(),
        ];
    }
}

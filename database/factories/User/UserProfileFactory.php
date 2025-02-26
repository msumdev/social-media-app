<?php

namespace Database\Factories\User;

use App\Models\User\InterestType;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

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
            'user' => null,
            'description' => $this->faker->text,
            'background_colour' => "#ffffff",
            'background_image' => "",
            'user_info_background_colour' => "#ffffff",
            'user_info_text_colour' => "#4182eb",
            'about_me_background_colour' => "#F8F9FA",
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}

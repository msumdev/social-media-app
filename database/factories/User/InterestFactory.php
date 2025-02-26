<?php

namespace Database\Factories\User;

use App\Models\User\InterestType;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User\User>
 */
class InterestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'interest_type_id' => InterestType::inRandomOrder()->first()->id,
            'user_id' => null,
        ];
    }
}

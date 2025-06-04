<?php

namespace Database\Factories\User;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User\User>
 */
class UserFilterFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'age_from' => 11,
            'age_to' => 120,
            'sexes' => [],
            'genders' => [],
            'countries' => [],
            'cities' => [],
            'keywords' => [],
            'interests' => [],
        ];
    }
}

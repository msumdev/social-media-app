<?php

namespace Database\Factories\User;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\File;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User\User>
 */
class InterestTypeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $interests = json_decode(File::get(resource_path('example_data/interest_types.json')), true);
        $randomInterest = array_rand($interests, 1);

        return [
            'label' => $interests[$randomInterest]['label'],
        ];
    }
}

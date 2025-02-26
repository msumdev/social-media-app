<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class GenderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $genders = [
            'Male',
            'Female',
            'Non-binary',
            'Genderqueer',
            'Transgender',
            'Genderfluid',
            'Agender',
            'Bigender',
            'Androgynous',
            'Two-Spirit',
        ];

        return [
            'name' => $genders[array_rand($genders)],
        ];
    }
}

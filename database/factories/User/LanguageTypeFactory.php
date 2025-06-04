<?php

namespace Database\Factories\User;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\File;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User\User>
 */
class LanguageTypeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $types = json_decode(File::get(resource_path('example_data/languages.json')), true);
        $randomType = array_rand($types, 1);

        return [
            'name' => $types[$randomType]['name'],
            'code' => $types[$randomType]['code'],
        ];
    }
}

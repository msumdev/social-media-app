<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\File;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class SexFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $sexes = json_decode(File::get(resource_path('example_data/sexes.json')), true);

        return [
            'label' => $sexes[0]['label'],
            'value' => $sexes[0]['value'],
        ];
    }
}

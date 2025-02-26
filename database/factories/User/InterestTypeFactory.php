<?php

namespace Database\Factories\User;

use App\Models\User\InterestType;
use App\Models\User\User;
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
        $interests = json_decode(File::get(database_path('data/interests.json')), true);

        return [
            'name' => $interests[0]['name']
        ];
    }
}

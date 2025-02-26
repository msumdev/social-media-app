<?php

namespace Database\Factories\Room;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class RoomFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $types = ['direct', 'group'];

        return [
            'name' => $this->faker->sentence,
            'type' => $types[array_rand($types)],
        ];
    }
}

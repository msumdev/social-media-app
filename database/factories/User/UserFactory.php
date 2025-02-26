<?php

namespace Database\Factories\User;

use App\Models\City;
use App\Models\Country;
use App\Models\Gender;
use App\Models\Sex;
use App\Models\User\Language;
use App\Models\User\SexualityType;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $country = Country::factory()
            ->hasCities(1)
            ->create();
        $dateOfBirth = fake()->dateTimeBetween('-100 years', '-16 years');

        return [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'username' => fake()->unique()->userName(),
            'country_id' => $country->id,
            'city_id' => $country->cities->first()->id,
            'sexuality_id' => SexualityType::factory(),
            'description' => fake()->sentence(),
            'date_of_birth' => $dateOfBirth,
            'sex_id' => Sex::factory(),
            'gender_id' => Gender::factory(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'status' => fake()->sentence(),
            'password' => static::$password ??= Hash::make('password'),
            'registered' => true,
            'banned' => false,
            'remember_token' => Str::random(10),
        ];
    }
}

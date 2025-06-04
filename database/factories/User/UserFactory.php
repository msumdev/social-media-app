<?php

namespace Database\Factories\User;

use App\Models\City;
use App\Models\Country;
use App\Models\Gender;
use App\Models\Sex;
use App\Models\User\Interest;
use App\Models\User\Language;
use App\Models\User\ProfilePicture;
use App\Models\User\SexualityType;
use App\Models\User\User;
use App\Models\User\UserFilter;
use App\Models\User\UserProfile;
use App\Models\User\UserSetting;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        if (env('APP_ENV') === 'testing') {
            $country = Country::factory()
                ->has(City::factory())
                ->create();

            return [
                'first_name' => fake()->firstName(),
                'last_name' => fake()->lastName(),
                'username' => fake()->unique()->userName(),
                'country_id' => $country->id,
                'city_id' => $country->cities()->first()->id,
                'sexuality_id' => SexualityType::factory(),
                'date_of_birth' => $this->faker->dateTimeBetween('-100 years', '-16 years'),
                'sex_id' => Sex::factory(),
                'gender_id' => Gender::factory(),
                'email' => fake()->unique()->safeEmail(),
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'registered' => true,
                'banned' => false,
                'remember_token' => Str::random(10),
            ];
        } else {
            $country = Country::inRandomOrder()->has('cities')->first();

            return [
                'first_name' => fake()->firstName(),
                'last_name' => fake()->lastName(),
                'username' => fake()->unique()->userName(),
                'country_id' => $country->id,
                'city_id' => $country->cities()->first()->id,
                'sexuality_id' => SexualityType::inRandomOrder()->value('id'),
                'date_of_birth' => $this->faker->dateTimeBetween('-100 years', '-16 years'),
                'sex_id' => Sex::inRandomOrder()->value('id'),
                'gender_id' => Gender::inRandomOrder()->value('id'),
                'email' => fake()->unique()->safeEmail(),
                'email_verified_at' => now(),
                'registered' => true,
                'banned' => false,
                'remember_token' => Str::random(10),
            ];
        }
    }

    /**
     * Configure any relationships while creating the user
     */
    public function configure(): static
    {
        return $this->afterCreating(function (User $user) {
            $user->languages()->save(Language::factory()->create(['user_id' => $user->id]));
            $user->interests()->save(Interest::factory()->create(['user_id' => $user->id]));
            $user->userProfile()->save(UserProfile::factory()->create(['user_id' => $user->id]));
            $user->profilePicture()->save(ProfilePicture::factory()->create(['user_id' => $user->id]));
            $user->userSetting()->save(UserSetting::factory()->create(['user_id' => $user->id]));
            $user->userFilter()->save(UserFilter::factory()->create(['user_id' => $user->id]));
        });
    }
}

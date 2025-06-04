<?php

namespace Database\Factories\User;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory
 */
class ProfilePictureFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $file = resource_path('/images/profile-pictures/'.rand(1, 10).'.jpg');
        $path = sha1(Str::random(16));

        Storage::disk('profile-pictures')->put(
            $path,
            File::get($file)
        );

        $url = Storage::disk('profile-pictures')->url($path);

        return [
            'path' => $path,
            'url' => $url,
        ];
    }
}

<?php

namespace Database\Factories\Posts;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Posts\PostAudioAsset>
 */
class PostAudioAssetFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $file = resource_path('/example_audio/sample-'.rand(0, 9).'.mp3');
        $path = sha1(Str::random(16));

        Storage::disk('post-audio')->put(
            $path,
            File::get($file)
        );

        $url = Storage::disk('post-audio')->url($path);

        return [
            'path' => $path,
            'url' => $url,
        ];
    }
}

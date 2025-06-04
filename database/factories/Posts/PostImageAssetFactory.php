<?php

namespace Database\Factories\Posts;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Posts\ImageAsset>
 */
class PostImageAssetFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $file = resource_path('/images/posts/image-'.rand(1, 700).'.jpg');
        $path = sha1(Str::random(16));

        Storage::disk('post-images')->put(
            $path,
            File::get($file)
        );

        $url = Storage::disk('post-images')->url($path);

        return [
            'path' => $path,
            'url' => $url,
        ];
    }
}

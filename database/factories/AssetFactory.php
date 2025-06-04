<?php

namespace Database\Factories;

use App\Models\Asset;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User\User>
 */
class AssetFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => null,
            'post_id' => null,
            'post_comment_id' => null,
            'profile_comment_id' => null,
            'is_profile' => false,
            'path' => null,
            'type' => null,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }

    public function post_image(): AssetFactory
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => Asset::POST_IMAGE,
            ];
        });
    }

    public function post_audio(): AssetFactory
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => Asset::POST_AUDIO,
            ];
        });
    }

    public function profile_picture(): AssetFactory
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => Asset::PROFILE_PICTURE,
                'is_profile' => true,
            ];
        });
    }

    public function post_comment_audio(): AssetFactory
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => Asset::POST_COMMENT_AUDIO,
            ];
        });
    }

    public function profile_comment_audio(): AssetFactory
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => Asset::PROFILE_COMMENT_AUDIO,
            ];
        });
    }
}

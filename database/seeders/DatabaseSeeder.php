<?php

namespace Database\Seeders;

use App\Models\Asset;
use App\Models\Settings;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class DatabaseSeeder extends Seeder
{
    /**
     * @var string[]
     */
    private array $mongoCollections = [
        'app_logs',
        'post_comments',
        'posts',
        'room_messages',
        'user_profiles',
    ];

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->resetS3Storage();
        $this->dropMongoCollections();

        $this->call([
            GenderSeeder::class,
            SexSeeder::class,
            SexualityTypeSeeder::class,
            CountryAndCitySeeder::class,
            InterestTypeSeeder::class,
            ReportReasonSeeder::class,
            UserSeeder::class,
            PostSeeder::class,
        ]);

        Settings::create([
            'minimum_age' => 10,
            'maximum_age' => 120,
        ]);
    }

    public function resetS3Storage(): void
    {
        $disks = [
            Asset::POST_IMAGE,
            Asset::POST_AUDIO,
            Asset::PROFILE_PICTURE,
            Asset::POST_COMMENT_AUDIO,
            Asset::PROFILE_COMMENT_AUDIO,
        ];

        foreach ($disks as $disk) {
            $files = Storage::disk($disk)->allFiles();

            foreach ($files as $file) {
                Storage::disk($disk)->delete($file);
            }
        }
    }

    public function dropMongoCollections(): void
    {
        foreach ($this->mongoCollections as $collection) {
            Schema::connection('mongodb')->drop($collection);
        }
    }
}

<?php

namespace Database\Seeders;

use App\Facades\ElasticSearchServiceFacade;
use App\Http\Requests\Chat\Message\CreateMessageRequest;
use App\Models\Asset;
use App\Models\Country;
use App\Models\Gender;
use App\Models\Posts\Post;
use App\Models\Posts\PostComment;
use App\Models\ReportReason;
use App\Models\Room\Room;
use App\Models\Room\RoomMember;
use App\Models\Settings;
use App\Models\Sex;
use App\Models\User\Friend;
use App\Models\User\Interest;
use App\Models\User\InterestType;
use App\Models\User\Language;
use App\Models\User\LanguageType;
use App\Models\User\SexualityType;
use App\Models\User\User;
use App\Models\User\UserProfile;
use App\Models\User\UserSetting;
use App\Services\Chat\Room\RoomMessageService;
use App\Services\Chat\Room\RoomService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Benchmark;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use MongoDB\Collection;

class DatabaseSeeder extends Seeder
{
    /**
     * @var int
     */
    const POST_COUNT = 10;

    /**
     * @var int
     */
    const USER_COUNT = 20;

    /**
     * @var int
     */
    const COMMENT_COUNT = 5;

    /**
     * @var string[] $mongoCollections
     */
    private array $mongoCollections = [
        'assets',
        'post_comments',
        'posts',
        'room_messages',
        'user_profiles'
    ];
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        ray("resetting S3 storage took " . (Benchmark::measure(fn() => $this->resetS3Storage()) / 1000) . " seconds")->blue();
        ray("dropping Mongo collections took " . (Benchmark::measure(fn() => $this->dropMongoCollections()) / 1000) . " seconds")->blue();
        ray("setting up cities took " . (Benchmark::measure(fn() => $this->setupCities()) / 1000) . " seconds")->blue();
        ray("setting up languages took " . (Benchmark::measure(fn() => $this->setupLanguages()) / 1000) . " seconds")->blue();
        ray("setting up countries took " . (Benchmark::measure(fn() => $this->setupCountries()) / 1000) . " seconds")->blue();
        ray("setting up genders took " . (Benchmark::measure(fn() => $this->setupGenders()) / 1000) . " seconds")->blue();
        ray("setting up sexes took " . (Benchmark::measure(fn() => $this->setupSexes()) / 1000) . " seconds")->blue();
        ray("setting up interests took " . (Benchmark::measure(fn() => $this->setupInterests()) / 1000) . " seconds")->blue();
        ray("setting up sexualities took " . (Benchmark::measure(fn() => $this->setupSexualities()) / 1000) . " seconds")->blue();
        ray("setting up report reasons took " . (Benchmark::measure(fn() => $this->setupReportReasons()) / 1000) . " seconds")->blue();
        ray("setting up users took " . (Benchmark::measure(fn() => $this->setupUsers()) / 1000) . " seconds")->blue();
        ray("setting up posts took " . (Benchmark::measure(fn() => $this->setupPosts()) / 1000) . " seconds")->blue();


        Settings::create([
            'minimum_age' => 10,
            'maximum_age' => 120
        ]);
    }

    /**
     * @return void
     */
    public function resetS3Storage(): void
    {
        $disks = [
            Asset::POST_IMAGE,
            Asset::POST_AUDIO,
            Asset::PROFILE_PICTURE,
            Asset::POST_COMMENT_AUDIO,
            Asset::PROFILE_COMMENT_AUDIO
        ];

        foreach ($disks as $disk) {
            $files = Storage::disk($disk)->allFiles();

            foreach ($files as $file) {
                Storage::disk($disk)->delete($file);
            }
        }
    }

    /**
     * @return void
     */
    public function dropMongoCollections(): void
    {
        foreach ($this->mongoCollections as $collection) {
            Schema::connection('mongodb')->drop($collection);
        }
    }

    /**
     * @return void
     */
    private function setupCities(): void
    {
        $cities = json_decode(File::get(database_path('data/' . env('CITIES_JSON'))), true);
        $chunkSize = 1000;
        $citiesChunks = array_chunk($cities, $chunkSize);

        foreach ($citiesChunks as $chunk) {
            DB::table('cities')->insert($chunk);
        }
    }

    /**
     * @return void
     */
    private function setupLanguages(): void
    {
        $languages = json_decode(File::get(database_path('data/languages.json')), true);
        foreach ($languages as $language) {
            LanguageType::create([
                'name' => $language['name'],
                'code' => $language['code']
            ]);
        }
    }

    /**
     * @return void
     */
    private function setupCountries(): void
    {
        $countries = json_decode(File::get(database_path('data/' . env('COUNTRIES_JSON'))), true);
        foreach ($countries as $country) {
            Country::create([
                'id' => $country['id'],
                'name' => $country['name'],
                'code' => $country['code']
            ]);
        }
    }

    /**
     * @return void
     */
    private function setupGenders(): void
    {
        $genders = json_decode(File::get(database_path('data/genders.json')), true);
        foreach ($genders as $gender) {
            Gender::create([
                'name' => $gender['name']
            ]);
        }
    }

    /**
     * @return void
     */
    public function setupSexes(): void
    {
        $sexes = json_decode(File::get(database_path('data/sexes.json')), true);
        foreach ($sexes as $sex) {
            Sex::create([
                'name' => $sex['name'],
                'code' => $sex['code']
            ]);
        }
    }

    /**
     * @return void
     */
    public function setupInterests(): void
    {
        $interests = json_decode(File::get(database_path('data/interests.json')), true);
        foreach ($interests as $interest) {
            InterestType::create([
                'name' => $interest['name']
            ]);
        }
    }

    /**
     * @return void
     */
    private function setupSexualities(): void
    {
        $sexualities = json_decode(File::get(database_path('data/sexualities.json')), true);
        foreach ($sexualities as $sexuality) {
            SexualityType::create([
                'name' => $sexuality['name']
            ]);
        }
    }

    /**
     * @return void
     */
    private function setupReportReasons(): void
    {
        $reportReasons = [
            'Spam',
            'Harassment',
            'Inappropriate Content',
            'Fake Information',
            'Hate Speech',
            'Copyright Infringement',
            'Privacy Violation',
            'Scam or Fraud',
            'Violence or Threat',
            'Other'
        ];

        foreach ($reportReasons as $reason) {
            ReportReason::create([
                'name' => $reason
            ]);
        }
    }

    /**
     * @return void
     */
    public function setupUsers(): void
    {
        $hash = Hash::make('&BrefT2D3UopN$s$');

        $users = User::factory()
            ->count(self::USER_COUNT)
            ->make()
            ->each(function ($user, $index) use ($hash) {
                $user->id = $index + 1;
                $user->email = 'test' . $user->id . '@test.com';
                $user->registered = 1;
                $user->password = $hash;
            })
            ->toArray();

        User::insert($users);

        $adminUser = User::factory()
            ->make([
                'id' => count($users) + 1,
                'email' => 'admin@test.com',
                'registered' => 1,
                'password' => $hash,
            ])
            ->toArray();

        User::insert($adminUser);

        $users[] = $adminUser;

        $roomOwner = User::where('email', 'admin@test.com')->first();

        for ($i = 0; $i < count($users); $i++) {
            $user = $users[$i];

            Asset::factory()
                ->state(function (array $attributes) use ($user) {
                    $file = database_path('seeders/examples/images/' . rand(1, 3) . '.png');
                    $path = Asset::generateName();

                    // Store the file in the appropriate disk
                    Storage::disk(Asset::PROFILE_PICTURE)->put(
                        $path,
                        File::get($file)
                    );

                    return [
                        'type' => Asset::PROFILE_PICTURE,
                        'path' => $path,
                        'is_profile' => true,
                        'user_id' => $user['id'],
                        // Any additional attributes can be added here
                    ];
                })
                ->create();

            UserProfile::create([
                'user_id' => $user['id'],
            ]);

            UserSetting::create([
                'user_id' => $user['id'],
                'render_media' => true
            ]);

            Language::create([
                'user_id' => $user['id'],
                'language_type_id' => LanguageType::inRandomOrder()->first()->id
            ]);

            Interest::factory()
                ->count(2)
                ->state(function (array $attributes) use ($user) {
                    return [
                        'user_id' => $user['id']
                    ];
                })
                ->create();

            if ($adminUser === $user['id']) {
                continue;
            }

            Friend::create([
                'user_id' => $adminUser['id'],
                'friend_id' => $user['id'],
                'status' => 1
            ]);

            Friend::create([
                'user_id' => $user['id'],
                'friend_id' => $adminUser['id'],
                'status' => 1
            ]);
        }

        $messages = json_decode(File::get(database_path('data/messages.json')), true);
        $admin = User::where('email', 'admin@test.com')->first();
        $users = User::get();
        $otherUser = $users->first();

        $roomService = new RoomService();
        $roomService->create(
            type: 'direct',
            user: $admin,
            members: [$otherUser->id]
        );

        $roomService->create(
            type: 'group',
            user: $admin,
            members: $users->pluck('id')->toArray()
        );
    }

    /**
     * @return void
     */
    public function setupPosts(): void
    {
        $posts = Post::factory()
            ->count(self::POST_COUNT)
            ->make()
            ->each(function ($post) {
                $post->user_id = User::inRandomOrder()->first()->id;
            })
            ->toArray();

        Post::insert($posts);

        foreach ($posts as $post) {
            $comments = PostComment::factory()
                ->count(self::COMMENT_COUNT)
                ->make()
                ->each(function ($comment) use ($post) {
                    $comment->post_id = $post['_id'];
                    $comment->user_id = User::inRandomOrder()->first()->id;
                })
                ->toArray();

            PostComment::insert($comments);

            $assets = Asset::factory()
                ->count(2)
                ->make()
                ->each(function ($asset) use ($post) {
                    $asset->type = Asset::POST_IMAGE;

                    $asset->path = Asset::generateName();
                    $file = database_path('seeders/examples/images/' . rand(1, 3) . '.png');

                    Storage::disk($asset->type)->put($asset->path, File::get($file));

                    $asset->user_id = $post['user_id'];
                    $asset->post_id = $post['_id'];
                })
                ->toArray();

            $asset = new Asset();
            $asset->insert($assets);
        }
    }
}

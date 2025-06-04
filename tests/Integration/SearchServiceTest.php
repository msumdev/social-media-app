<?php

namespace Tests\Integration;

use App\Models\City;
use App\Models\Country;
use App\Models\Gender;
use App\Models\Sex;
use App\Models\User\User;
use App\Services\SearchService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SearchServiceTest extends TestCase
{
    use DatabaseMigrations;
    use WithFaker;

    private SearchService $searchService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpFaker();
        $this->searchService = app(SearchService::class);
    }

    public function test_filtering_users_by_sexes(): void
    {
        $sex = Sex::factory()->create([
            'label' => 'test',
            'value' => 'test',
        ]);

        User::factory()->count(10)->create();

        $user = User::factory()->create(['sex_id' => $sex->id]);

        $this->user->userFilter->sexes = [$sex->id];
        $this->user->userFilter->save();

        $response = $this->searchService->search($this->user->userFilter);
        $data = $response->pluck('id')->toArray();

        $this->assertContains($user->id, $data);
    }

    public function test_filtering_users_by_genders(): void
    {
        $gender = Gender::factory()->create([
            'label' => 'test',
        ]);

        User::factory()->count(10)->create();

        $user = User::factory()->create(['gender_id' => $gender->id]);

        $this->user->userFilter->genders = [$gender->id];
        $this->user->userFilter->save();

        $response = $this->searchService->search($this->user->userFilter);
        $data = $response->pluck('id')->toArray();

        $this->assertContains($user->id, $data);
    }

    public function test_filtering_users_by_country(): void
    {
        $country = Country::factory()->create([
            'label' => 'test',
            'value' => 'some code',
        ]);

        User::factory()->count(10)->create();

        $user = User::factory()->create(['country_id' => $country->id]);

        $this->user->userFilter->countries = [$country->id];
        $this->user->userFilter->save();

        $response = $this->searchService->search($this->user->userFilter);
        $data = $response->pluck('id')->toArray();

        $this->assertContains($user->id, $data);
    }

    public function test_filtering_users_by_city(): void
    {
        $country = Country::factory()
            ->has(City::factory())
            ->create();

        $city = $country->cities->first();

        User::factory()->count(10)->create();

        $user = User::factory()->create(['city_id' => $city->id]);

        $this->user->userFilter->cities = [$city->id];
        $this->user->userFilter->save();

        $response = $this->searchService->search($this->user->userFilter);

        $data = $response->pluck('id')->toArray();

        $this->assertContains($user->id, $data);
    }

    public function test_filtering_users_by_keywords(): void
    {
        $targetWord = 'Pneumonoultramicroscopicsilicovolcanoconiosis';
        $sentence = $this->faker->sentence().' '.$targetWord;
        $otherSentence = $this->faker->sentence().' '.$targetWord;

        $user = User::factory()->create();

        $user->userProfile->description = $sentence;
        $user->userProfile->save();

        $otherUser = User::factory()->create();
        $otherUser->userProfile->status = $otherSentence;
        $otherUser->userProfile->save();

        $this->user->userFilter->keywords = $targetWord;
        $this->user->userFilter->save();

        $response = $this->searchService->search($this->user->userFilter);

        $this->assertStringContainsString($targetWord, $response->pluck('userProfile.description')[0]);
        $this->assertStringContainsString($targetWord, $response->pluck('userProfile.status')[1]);
    }

    public function test_filtering_users_by_ages(): void
    {
        User::factory()->count(10)->create([
            'date_of_birth' => Carbon::now()->subYears(20),
        ]);

        $user = User::factory()->create([
            'date_of_birth' => Carbon::now()->subYears(40),
        ]);

        $this->user->userFilter->age_from = 39;
        $this->user->userFilter->save();

        $response = $this->searchService->search($this->user->userFilter);
        $data = $response->pluck('id')->toArray();

        $this->assertContains($user->id, $data);

        $this->user->userFilter->age_from = 41;
        $this->user->userFilter->save();

        $response = $this->searchService->search($this->user->userFilter);
        $data = $response->pluck('id')->toArray();

        $this->assertNotContains($user->id, $data);
    }
}

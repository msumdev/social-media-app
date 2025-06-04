<?php

namespace Tests\Feature;

use App\Jobs\Auth\NewUser;
use App\Models\City;
use App\Models\Country;
use App\Models\Gender;
use App\Models\Sex;
use App\Models\User\InterestType;
use App\Models\User\SexualityType;
use App\Models\User\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Bus;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use DatabaseMigrations, WithFaker;

    private string $email;

    private string $password;

    private array $userData;

    protected function setUp(): void
    {
        parent::setUp();

        $country = Country::factory()
            ->has(City::factory())
            ->create();

        $this->email = $this->faker->email;
        $this->password = 'Testing1234!';
        $this->userData = [
            'username' => $this->faker->userName,
            'email' => $this->email,
            'date_of_birth' => Carbon::now()->subYear(18),
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'sex_id' => Sex::factory()->create()->id,
            'gender_id' => Gender::factory()->create()->id,
            'country_id' => $country->id,
            'city_id' => $country->cities()->first()->id,
            'sexuality_id' => SexualityType::factory()->create()->id,
            'interests' => InterestType::factory()->count(2)->create()->pluck('id')->toArray(),
            'password' => $this->password,
            'confirm_password' => $this->password,
        ];
    }

    /**
     * A basic feature test example.
     */
    public function test_registration_page_is_accessible(): void
    {
        $response = $this->get('/registration');

        $response->assertStatus(200);
    }

    public function test_person_who_is_underage_cannot_register(): void
    {
        $this->userData['date_of_birth'] = Carbon::now()->subYear(5);

        $response = $this->post('/registration', $this->userData);

        $errors = session('errors');

        $response->assertSessionHasErrors(['date_of_birth']);

        $this->assertEquals('You must be at least 12 years old to register.', $errors->first('date_of_birth'));
    }

    public function test_password_must_patch_during_registration(): void
    {
        $this->userData['password'] = 'fedcba';
        $this->userData['confirm_password'] = 'abcdef';

        $response = $this->post('/registration', $this->userData);

        $response->assertSessionHasErrors(['password', 'confirm_password']);
    }

    public function test_user_is_able_to_register(): void
    {
        $response = $this->post('/registration', $this->userData);

        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    public function test_user_receives_confirmation_email(): void
    {
        Bus::fake();

        $this->post('/registration', $this->userData);

        Bus::assertDispatched(NewUser::class);
    }

    public function test_verify_token_is_generated_and_is_confirmable(): void
    {
        $this->post('/registration', $this->userData);

        $token = User::where('email', $this->email)->first()->token;

        $this->get('/registration/confirm/'.$token);

        $user = User::where('email', $this->email)->first();

        $this->assertEquals($user->registered, 1);
        $this->assertNull($user->token);
    }

    public function test_user_can_login_after_registering(): void
    {
        $this->post('/registration', $this->userData);

        $token = User::where('email', $this->email)->first()->token;

        $this->get('/registration/confirm/'.$token);

        $this->post('/login', [
            'email' => $this->email,
            'password' => $this->password,
        ])->assertStatus(302)
            ->assertRedirect('/');
    }
}

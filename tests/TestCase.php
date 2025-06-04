<?php

namespace Tests;

use App\Models\User\User;
use Database\Seeders\InterestTypeSeeder;
use Database\Seeders\SexualityTypeSeeder;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed([
            SexualityTypeSeeder::class,
            InterestTypeSeeder::class,
        ]);

        $this->user = User::factory()->create();

        $dbName = 'testdb_'.getenv('UNIQUE_TEST_TOKEN');
    }
}

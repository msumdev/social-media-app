<?php

namespace Database\Seeders;

use App\Models\User\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    const NORMAL_USER_COUNT = 50;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $hash = Hash::make('&BrefT2D3UopN$s$');
        $users = [
            'user',
            'admin',
        ];

        $defaultUsers = User::factory()->count(self::NORMAL_USER_COUNT)->create();

        foreach ($users as $user) {
            $user = User::factory()->create([
                'email' => "test-$user@test.com",
                'registered' => true,
                'date_of_birth' => new Carbon('1996-08-07'),
                'password' => $hash,
            ]);

            $user->friends()->saveMany($defaultUsers);
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\Posts\Post;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Post::factory()
            ->count(10)
            ->create();
    }
}

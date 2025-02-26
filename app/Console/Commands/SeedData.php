<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class SeedData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:seed-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Initial command used to seed the database with data.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        File::cleanDirectory(public_path('images/profile-pictures'));
        File::cleanDirectory(public_path('storage/posts'));

        Artisan::call('db:seed', [
            '--class' => 'DatabaseSeeder',
            '--force' => true,
        ]);
    }
}

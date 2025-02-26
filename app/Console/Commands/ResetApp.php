<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class ResetApp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:reset';

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
//        Schema::delete('*');

        Artisan::call('migrate:refresh', ['--force' => true]);
//        Artisan::call('app:delete-all-indexes');
//        Artisan::call('app:create-indexes');
        Artisan::call('app:seed-data');
    }
}

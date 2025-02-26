<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PDPhilip\Elasticsearch\Schema\Schema;

class DeleteAllIndexes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:delete-all-indexes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Schema::delete('*');
    }
}

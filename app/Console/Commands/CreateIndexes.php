<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PDPhilip\Elasticsearch\Schema\IndexBlueprint;
use PDPhilip\Elasticsearch\Schema\Schema;

class CreateIndexes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-indexes';

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
        $dates = [
            date('Y-m'),
            date('Y-m', strtotime('+1 month'))
        ];

        foreach ($dates as $date) {
            Schema::createIfNotExists('assets-' . $date, function (IndexBlueprint $index) {
                $index->keyword('user');
                $index->keyword('post_id');
                $index->keyword('post_comment_id');
                $index->keyword('profile_comment_id');
                $index->boolean('is_profile');
                $index->text('path');
                $index->keyword('type');
            });

            Schema::createIfNotExists('app-logs-' . $date, function (IndexBlueprint $index) {
                $index->keyword('user');
                $index->keyword('profile');
                $index->keyword('ip_address');
                $index->keyword('user_agent');
                $index->keyword('type');
            });

            Schema::createIfNotExists('room-messages-' . $date, function (IndexBlueprint $index) {
                $index->integer('room_id');
                $index->integer('author_id');
                $index->text('content');
            });
        }

        Schema::createIfNotExists('posts', function (IndexBlueprint $index) {
            $index->integer('user_id');
            $index->text('content');
            $index->array('mentions');
            $index->array('hashtags');
        });

        Schema::createIfNotExists('post_comments', function (IndexBlueprint $index) {
            $index->keyword('post_id');
            $index->integer('user_id');
            $index->text('content');
            $index->array('mentions');
            $index->array('hashtags');
        });

        Schema::createIfNotExists('user_profiles', function (IndexBlueprint $index) {
            $index->integer('user');
            $index->text('description');
            $index->keyword('background_colour');
            $index->keyword('background_image');
            $index->keyword('user_info_background_colour');
            $index->keyword('user_info_text_colour');
            $index->keyword('about_me_background_colour');
        });

        Schema::createIfNotExists('profile_comments', function (IndexBlueprint $index) {
            $index->integer('user');
            $index->text('content');
            $index->array('mentions');
            $index->array('hashtags');
        });
    }
}

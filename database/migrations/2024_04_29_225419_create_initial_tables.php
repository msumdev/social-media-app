<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('genders')) {
            Schema::create('genders', function (Blueprint $table) {
                $table->id();
                $table->string('name');
            });
        }

        if (!Schema::hasTable('sexes')) {
            Schema::create('sexes', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('code');
            });
        }

        if (!Schema::hasTable('user_filters')) {
            Schema::create('user_filters', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id');
                $table->integer('age_from')->default(11);
                $table->integer('age_to')->default(120);
                $table->json('sexes')->default(json_encode([]));
                $table->json('genders')->default(json_encode([]));
                $table->json('countries')->default(json_encode([]));
                $table->integer('city')->nullable();
                $table->boolean('online')->nullable();
                $table->json('keywords')->default(json_encode([]));
                $table->string('username')->nullable();
                $table->json('interests')->default(json_encode([]));
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('interests')) {
            Schema::create('interests', function (Blueprint $table) {
                $table->id();
                $table->integer('user_id');
                $table->foreignId('interest_type_id');
            });
        }

        if (!Schema::hasTable('interest_types')) {
            Schema::create('interest_types', function (Blueprint $table) {
                $table->id();
                $table->string('name');
            });
        }

        if (!Schema::hasTable('settings')) {
            Schema::create('settings', function (Blueprint $table) {
                $table->id();
                $table->integer('minimum_age')->default(11);
                $table->integer('maximum_age')->default(120);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('post_likes')) {
            Schema::create('post_likes', function (Blueprint $table) {
                $table->id();
                $table->string('post_id')->constrained()->onDelete('cascade');
                $table->foreignId('user_id');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('post_comment_likes')) {
            Schema::create('post_comment_likes', function (Blueprint $table) {
                $table->id();
                $table->string('post_comment_id');
                $table->foreignId('user_id');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('post_comment_replies')) {
            Schema::create('post_comment_replies', function (Blueprint $table) {
                $table->id();
                $table->string('post_comment_id');
                $table->foreignId('user');
                $table->text('content');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('friends')) {
            Schema::create('friends', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->foreignId('friend_id')->constrained('users')->onDelete('cascade');
                $table->tinyInteger('status')->default(0);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('blocked_users')) {
            Schema::create('blocked_users', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id');
                $table->foreignId('blocked_user_id');
                $table->text('reason')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('languages')) {
            Schema::create('languages', function (Blueprint $table) {
                $table->id();
                $table->integer('user_id');
                $table->foreignId('language_type_id');
            });
        }

        if (!Schema::hasTable('language_types')) {
            Schema::create('language_types', function (Blueprint $table) {
                $table->id();
                $table->string('code');
                $table->string('name');
            });
        }

        if (!Schema::hasTable('followers')) {
            Schema::create('followers', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id');
                $table->foreignId('follower_id');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('sexuality_types')) {
            Schema::create('sexuality_types', function (Blueprint $table) {
                $table->id();
                $table->string('name');
            });
        }

        if (!Schema::hasTable('countries')) {
            Schema::create('countries', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('code');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('cities')) {
            Schema::create('cities', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->foreignId('country_id');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('profile_comment_likes')) {
            Schema::create('profile_comment_likes', function (Blueprint $table) {
                $table->id();
                $table->string('profile_comment_id');
                $table->foreignId('user');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('user_reports')) {
            Schema::create('user_reports', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id');
                $table->foreignId('reporter_id');
                $table->text('reason');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('post_reports')) {
            Schema::create('post_reports', function (Blueprint $table) {
                $table->id();
                $table->string('post_id');
                $table->foreignId('user_id');
                $table->text('description');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('post_report_reasons')) {
            Schema::create('post_report_reasons', function (Blueprint $table) {
                $table->id();
                $table->integer('post_report_id');
                $table->foreignId('reason_id');
            });
        }

        if (!Schema::hasTable('report_reasons')) {
            Schema::create('report_reasons', function (Blueprint $table) {
                $table->id();
                $table->string('name');
            });
        }

        if (!Schema::hasTable('rooms')) {
            Schema::create('rooms', function (Blueprint $table) {
                $table->id();
                $table->integer('hidden')->default(0);
                $table->string('name')->nullable();
                $table->enum('type', ['direct', 'group'])->default('direct');
                $table->boolean('archive')->default(false);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('room_members')) {
            Schema::create('room_members', function (Blueprint $table) {
                $table->id();
                $table->foreignId('room_id');
                $table->foreignId('user_id');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('room_message_reports')) {
            Schema::create('room_message_reports', function (Blueprint $table) {
                $table->id();
                $table->string('message_id');
                $table->foreignId('user_id');
                $table->text('description');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('room_message_counts')) {
            Schema::create('room_message_counts', function (Blueprint $table) {
                $table->id();
                $table->string('user_id');
                $table->foreignId('room_id');
                $table->integer('count')->default(0);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('room_message_report_reasons')) {
            Schema::create('room_message_report_reasons', function (Blueprint $table) {
                $table->id();
                $table->integer('room_message_report_id');
                $table->foreignId('reason_id');
            });
        }

        $adminRole = Role::create([
            'name' => 'admin',
            'guard_name' => 'web'
        ]);

        $moderatorRole = Role::create([
            'name' => 'moderator',
            'guard_name' => 'web'
        ]);

        $roomOwnerRole = Role::create([
            'name' => 'room-owner',
            'guard_name' => 'web'
        ]);

        Permission::create([
            'name' => 'view-user-reports',
            'guard_name' => 'web'
        ]);

        Permission::create([
            'name' => 'can-moderate-room',
            'guard_name' => 'web'
        ]);

        $adminRole->givePermissionTo('view-user-reports');
        $moderatorRole->givePermissionTo('view-user-reports');
        $roomOwnerRole->givePermissionTo('can-moderate-room');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('countries');
        Schema::dropIfExists('cities');
        Schema::dropIfExists('genders');
        Schema::dropIfExists('sexes');
        Schema::dropIfExists('user_filters');
        Schema::dropIfExists('interests');
        Schema::dropIfExists('interest_types');
        Schema::dropIfExists('settings');
        Schema::dropIfExists('friends');
        Schema::dropIfExists('users');
        Schema::dropIfExists('user_reports');
        Schema::dropIfExists('report_reasons');
        Schema::dropIfExists('post_reactions');
        Schema::dropIfExists('post_comment_reactions');
        Schema::dropIfExists('blocked_users');
        Schema::dropIfExists('languages');
        Schema::dropIfExists('followers');
        Schema::dropIfExists('sexuality_types');
        Schema::dropIfExists('profile_comment_likes');
        Schema::dropIfExists('post_reports');
        Schema::dropIfExists('post_report_reasons');
        Schema::dropIfExists('rooms');
        Schema::dropIfExists('room_members');
        Schema::dropIfExists('room_message_reports');
        Schema::dropIfExists('room_message_counts');
    }
};

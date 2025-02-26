<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                $table->id();
                $table->boolean('online')->default(false);
                $table->string('first_name');
                $table->string('last_name');
                $table->string('username');
                $table->integer('country_id');
                $table->integer('city_id')->nullable();
                $table->integer('sexuality_id')->nullable();
                $table->date('date_of_birth');
                $table->integer('sex_id');
                $table->integer('gender_id');
                $table->string('email')->unique();
                $table->tinyText('status')->nullable();
                $table->text('description')->nullable();
                $table->boolean('registered')->default(false);
                $table->string('token')->nullable();
                $table->string('password_reset_token')->nullable();
                $table->string('password_reset_at')->nullable();
                $table->timestamp('last_registration_email_sent_at')->nullable();
                $table->timestamp('email_verified_at')->nullable();
                $table->string('password');
                $table->string('api_token', 80)->unique()->nullable()->default(null);
                $table->integer('role')->default(0);
                $table->boolean('banned')->default(false);
                $table->longText('jwt_token')->nullable();
                $table->date('jwt_token_expires_at')->nullable();
                $table->rememberToken();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('user_settings')) {
            Schema::create('user_settings', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id');
                $table->boolean('render_media')->default(false);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('password_reset_tokens')) {
            Schema::create('password_reset_tokens', function (Blueprint $table) {
                $table->string('email')->primary();
                $table->string('token');
                $table->timestamp('created_at')->nullable();
            });
        }

        if (!Schema::hasTable('sessions')) {
            Schema::create('sessions', function (Blueprint $table) {
                $table->string('id')->primary();
                $table->foreignId('user_id')->nullable()->index();
                $table->string('ip_address', 45)->nullable();
                $table->text('user_agent')->nullable();
                $table->longText('payload');
                $table->integer('last_activity')->index();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('user_settings');
    }
};

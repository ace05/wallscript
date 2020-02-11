<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->string('password');
            $table->boolean('is_active')->default(1);
            $table->boolean('is_blocked')->default(0);
            $table->boolean('is_email_verified')->default(0);
            $table->datetime('last_login');
            $table->string('source')->nullable();
            $table->string('email_verification_token', 100)->nullable();
            $table->datetime('email_token_created')->nullable();
            $table->mediumText('social_data')->nullable();
            $table->string('cover_photo_position')->nullable();
            $table->boolean('is_admin')->default(0);
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('users');
    }
}

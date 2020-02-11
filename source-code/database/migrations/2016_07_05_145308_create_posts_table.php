<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->unsigned();
            $table->mediumText('message');
            $table->string('place')->nullable();
            $table->string('lat')->nullable();
            $table->string('lng')->nullable();
            $table->mediumText('external_data')->nullable();
            $table->bigInteger('wall_user_id')->unsigned()->nullable();
            $table->integer('comments_count')->default(0);
            $table->integer('share_count')->default(0);
            $table->integer('likes_count')->default(0);
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('wall_user_id')->references('id')->on('users');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('posts');
    }
}

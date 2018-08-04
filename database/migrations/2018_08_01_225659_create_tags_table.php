<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tags', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->timestamps();
        });
        Schema::create('user_has_tags', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('users_id')->references('id')->on('users');
            $table->integer('tags_id')->references('id')->on('tags');
            $table->timestamps();
        });
        Schema::create('media_has_tags', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('users_id')->references('id')->on('users');
            $table->integer('medias_id')->references('id')->on('medias');
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
        Schema::dropIfExists('tags');
        Schema::dropIfExists('user_has_tags');
        Schema::dropIfExists('medias_has_tags');
    }
}

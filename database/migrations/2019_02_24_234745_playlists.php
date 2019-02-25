<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Playlists extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('playlists', function (Blueprint $table) {
          $table->increments('id');
          $table->string('title');
          $table->text('description')->nullable();
          $table->boolean('public')->default(false);
          $table->integer('user_id')->references('id')->on('users');
          $table->timestamps();
        });
        
        Schema::create('playlist_media', function (Blueprint $table) {
          $table->increments('id');
          $table->integer('media_id')->references('id')->on('medias');
          $table->integer('playlist_id')->references('id')->on('playlists');
          $table->integer('order')->default(0);
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
        //
        Schema::dropIfExists('playlists');
        Schema::dropIfExists('playlist_media');
    }
}

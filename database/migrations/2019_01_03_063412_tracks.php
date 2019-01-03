<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Tracks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('tracks', function (Blueprint $table) {
            $table->increments('id');
            $table->string('lang')->default('en');
            $table->string('type')->default('vtt');
            $table->string('source')->default('');
            $table->string('media_id')->references('id')->on('medias');
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
        Schema::dropIfExists('tracks');
    }
}

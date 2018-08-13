<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMediasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('medias', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->unique();
            $table->string('type')->default('');
            $table->string('poster_source')->default('');
            $table->text('source');
            $table->text('duration')->default('0');
            $table->text('description')->nullable();
            $table->integer('users_id')->references('id')->on('users');
            $table->integer('category_id')->nullable()->references('id')->on('categories');
            $table->integer('next_id')->nullable()->references('id')->on('medias')->default(0);
            $table->integer('views')->default(0);
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
        Schema::dropIfExists('videos');
    }
}

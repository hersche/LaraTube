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
            $table->string('duration')->default('0');
            $table->text('description')->nullable();
            $table->integer('user_id')->references('id')->on('users');
            $table->integer('category_id')->nullable()->references('id')->on('categories');
            $table->integer('next_id')->nullable()->references('id')->on('medias')->default(0);
            $table->integer('views')->default(0);
            $table->enum('allowed_group', ['public', 'family', 'acquaintances', 'close_friends'])->default('public');
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

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
            $table->string('base_type')->default('');
            $table->string('language')->default('');
            $table->string('file_type')->default('');
            $table->string('poster_source')->default('');
            //$table->text('source');
            $table->text('description')->nullable();
            $table->integer('user_id')->references('id')->on('users');
            $table->integer('category_id')->references('id')->on('categories')->default(0);
            $table->integer('next_id')->nullable()->references('id')->on('medias')->default(0);
            /*$table->integer('views')->default(0);
            $table->integer('intro_start')->default(0);
            $table->integer('intro_end')->default(0);
            $table->integer('outro_start')->default(0);
            $table->integer('outro_end')->default(0);*/
            $table->integer('license_id')->default(0);
            $table->enum('allowed_group', ['public', 'family', 'acquaintances', 'close_friends'])->default('public');
            $table->timestamps();
        });
        
        Schema::create('media_sources', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->default('');
            $table->string('type')->default('');
            $table->string('source_type')->default('');
            $table->text('source');
            $table->integer('source_prio')->default(0);
            $table->string('file_type')->default('');
            $table->string('duration')->default('0');
            // $table->integer('next_id')->nullable()->references('id')->on('medias')->default(0);
            $table->integer('media_id')->nullable()->references('id')->on('medias')->default(0);
            $table->timestamps();
        });
        
        Schema::create('media_chapters', function (Blueprint $table) {
            $table->increments('id')->unique();
            $table->string('title');
            $table->integer('position')->default(0);
            $table->integer('media_id')->nullable()->references('id')->on('medias')->default(0);
            $table->timestamps();
        });
        Schema::create('media_views', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('seconds')->default(0);
            $table->integer('user_id')->nullable()->references('id')->on('users');
            $table->integer('media_id')->nullable()->references('id')->on('medias');
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
        Schema::dropIfExists('medias');
        Schema::dropIfExists('media_sources');
        Schema::dropIfExists('media_chapter');
        Schema::dropIfExists('media_watch');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
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

      Schema::create('password_securities', function (Blueprint $table) {
        $table->increments('id');
        $table->integer('user_id');
        $table->boolean('enabled')->default(false);
        $table->string('secret')->nullable();
        $table->timestamps();
      });
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->default('');
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->string('roles')->default('');
            $table->text('bio')->nullable();
            $table->boolean('public')->default(false);
            $table->string('avatar')->default('');
            $table->string('background')->default('');
            $table->rememberToken();
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
      Schema::dropIfExists('password_securities');
      Schema::dropIfExists('users');
    }
}

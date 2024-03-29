<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('user_roles', function (Blueprint $table) {
        $table->increments('id');
        $table->integer('user_id')->unsigned();
        $table->integer('role_id')->unsigned();

        //when either of the foreign models is deleted so is this
        //record
        $table->foreign('user_id')->references('id')
          ->on('users')->onDelete('cascade');
        $table->foreign('role_id')->references('id')
          ->on('roles')->onDelete('cascade');

        //no duplicate role assigned to user
        $table->unique(['role_id', 'user_id']);
      });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::dropIfExists('user_roles');
    }
}

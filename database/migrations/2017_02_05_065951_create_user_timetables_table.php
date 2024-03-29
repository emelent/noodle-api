<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserTimetablesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('user_timetables', function (Blueprint $table) {
      $table->increments('id');
      $table->timestamps();
      $table->integer('user_id')->unsigned();
      $table->integer('timetable_id')->unsigned();

      //if user or timetable is deleted, delete record
      $table->foreign('user_id')->references('id')
        ->on('users')->onDelete('cascade');
      $table->foreign('timetable_id')->references('id')
        ->on('timetables')->onDelete('cascade');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('user_timetables');
  }
}

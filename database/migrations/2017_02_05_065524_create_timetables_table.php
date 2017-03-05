<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTimetablesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('timetables', function (Blueprint $table) {
      $table->increments('id');
      $table->timestamps();
      $table->string('hash')->nullable()->unique();
      $table->string('moduleDna')->nullable();
      $table->integer('creator_id')->unsigned();

      $table->foreign('creator_id')->references('id')
        ->on('users')->onDelete('cascade');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
      Schema::dropIfExists('timetables');
  }
}

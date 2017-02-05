<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('events', function (Blueprint $table) {
      $table->increments('id');
      $table->timestamps();
      $table->string('name');
      $table->tinyInteger('day');
      $table->time('start');
      $table->time('end');
      $table->date('date')->nullable();
      $table->tinyInteger('language');
      $table->tinyInteger('group');
      $table->foreign('creator_id')->references('id')
        ->on('users');
      //if a module is deleted so are it's related events
      $table->foreign('module_id')->references('id')
        ->on('modules')->onDelete('cascade');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('events');
  }
}

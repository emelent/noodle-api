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
      $table->tinyInteger('group')->unsigned()->nullable();
      $table->integer('creator_id')->unsigned()->nullable();
      $table->integer('module_id')->unsigned();

      $table->foreign('creator_id')->references('id')
        ->on('users')->onDelete('cascade');
      //if a module is deleted so are it's related events
      $table->foreign('module_id')->references('id')
        ->on('modules')->onDelete('cascade');

      //create composite key from name, module_id, group
      $table->unique(['group', 'module_id', 'name']);
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

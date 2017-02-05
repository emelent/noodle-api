<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTimetableEventsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('timetable_events', function (Blueprint $table) {
      $table->increments('id');
      $table->timestamps();

      //if event or timetable is deleted, delete record
      $table->foreign('timetable_id')->references('id')
        ->on('timetables')->onDelete('cascade');
      $table->foreign('event_id')->references('id')
        ->on('events')->onDelete('cascade');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('timetable_events');
  }
}

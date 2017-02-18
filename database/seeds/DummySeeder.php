<?php

use Illuminate\Database\Seeder;

use App\User;
use App\Module;
use App\Timetable;
use App\Event;

const NUM_USERS = 10;
const NUM_MODULES = 15;
const NUM_EVENTS = 30;
const NUM_TABLES = 10;

class DummySeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    //clear respective db tables
    User::truncate();
    Module::truncate();
    Event::truncate();
    Timetable::truncate();


    //-- CREATE MODEL RECORDS
    
    //create modules
    factory(Module::class, NUM_MODULES)->create();

    //create module events
    factory(Event::class, NUM_EVENTS)->create();

    //create users
    factory(User::class, NUM_USERS)->create()->each(function($u){
      $numSelectedModules = rand(2, NUM_MODULES);
      //add random modules for user
      for($i = 0; $i < $numSelectedModules; $i++){
        DB::table('user_modules')->insert([
          'module_id' =>  rand(1, NUM_MODULES),
          'user_id' => $u->id
        ]);
      }
    });

    //create tables
    factory(Timetable::class, NUM_TABLES)->create()->each(function($t){
      $numSelectedEvents = rand(1, NUM_EVENTS);
      $numSelectedUsers = rand(0, NUM_USERS);
    
      //add random events for timetable
      for($i = 0; $i < $numSelectedEvents; $i++){
        DB::table('timetable_events')->insert([
          'event_id' =>  rand(1, NUM_MODULES),
          'timetable_id' => $t->id
        ]);
      }

      //add random users of timetable
      for($i=0; $i < $numSelectedUsers; $i++){
        DB::table('user_timetables')->insert([
          'timetable_id' => $t->id,
          'user_id' => rand(1, NUM_USERS)
        ]);
      }
    });
  }
}

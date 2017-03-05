<?php

use Illuminate\Database\Seeder;

use App\User;
use App\Module;
use App\Timetable;
use App\Event;
use App\Role;

const NUM_USERS = 10;
const NUM_MODULES = 15;
const NUM_EVENTS = 30;
const NUM_TABLES = 10;

class DummySeeder extends Seeder
{

  /**
   * Disable foreign key checks on Sqlite3 database or MYSQL
   *
   * @return void
   */
  public function disableForeignKeyChecks(){
    try{
        DB::statement('PRAGMA foreign_keys = OFF');
    }catch(PDOException $e){
      try{
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
      }catch(PDOException $e){}
    }
  }

  /**
   * Enable foreign key checks on Sqlite3 database or MYSQL
   *
   * @return void
   */
  public function enableForeignKeyChecks(){
    try{
        DB::statement('PRAGMA foreign_keys = ON');
    }catch(PDOException $e){
      try{
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
      }catch(PDOException $e){}
    }
  }
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {

    //clear respective db tables
    $this->disableForeignKeyChecks();
    User::truncate();
    Module::truncate();
    Event::truncate();
    Timetable::truncate();
    Role::truncate();


    //-- CREATE MODEL RECORDS
    
    //create modules
    factory(Module::class, NUM_MODULES)->create();

    //create module events
    factory(Event::class, NUM_EVENTS)->create();

    //create roles
    foreach(['admin', 'user'] as $role){
      DB::table('roles')->insert([
        'role'  => $role
      ]);
    }

    //create users
    factory(User::class, NUM_USERS)->create()->each(function($u, $key){

      //--------
      // SELECT RANDOM MODULES FOR USER
      //---------

      $numSelectedModules = rand(2, NUM_MODULES-1);

      //populate available indexes
      $availableModules = [];
      for($i=0; $i < NUM_MODULES ; $i++)
        array_push($availableModules, $i+1);
      
      //add random modules for user
      for($i = 0; $i < $numSelectedModules; $i++){
        $index = rand(0, count($availableModules) - 1);
        DB::table('user_modules')->insert([
          'module_id' =>  $availableModules[$index],
          'user_id' => $u->id
        ]);
        //to prevent duplicates remove selected element
        //from availableModules array
        array_splice($availableModules, $index, 1);
      }
      //---------------------------------

      //-------
      // SET USER ROLES
      //-------

      //add user role
      DB::table('user_roles')->insert([
        'user_id' => $u->id,
        'role_id' => 2
      ]);

      //Make first user admin
      if($key == 0){
        //add admin role
        DB::table('user_roles')->insert([
          'user_id' => $u->id,
          'role_id' => 1
        ]);
      }
      //----------------------------------
    });

    //create tables
    factory(Timetable::class, NUM_TABLES)->create()->each(function($timetable){
      $numSelectedEvents = rand(1, NUM_EVENTS);
      $numSelectedUsers = rand(0, NUM_USERS);

      $availableEvents  = [];
      $events = [];
      $modules = [];
      for($i=0; $i < NUM_EVENTS; $i++)
        array_push($availableEvents, $i + 1);
    
      //add random events for timetable
      for($i = 0; $i < $numSelectedEvents; $i++){
        $index = rand(0, count($availableEvents) -1);
        DB::table('timetable_events')->insert([
          'event_id' =>  $availableEvents[$index],
          'timetable_id' => $timetable->id
        ]);

        $event = Event::findOrFail($availableEvents[$index]);
        array_push($events, $event->id);
        array_push($modules, $event->module_id);
        // unset($availableEvents[$index]);
        array_splice($availableEvents, $index, 1);
      }

      //generate timetable hash and moduleDna
      asort($events);
      $modules = array_unique($modules);
      asort($modules);

      $timetable->hash = implode('#', $events);
      $timetable->moduleDna = implode('#', $modules);
      $timetable->save();
      //add random users of timetable
      $availableUsers = [];
      for($i=0; $i < NUM_EVENTS; $i++)
        array_push($availableUsers, $i + 1);

      for($i=0; $i < $numSelectedUsers; $i++){
        $index = rand(0, count($availableUsers) -1);
        DB::table('user_timetables')->insert([
          'timetable_id' => $timetable->id,
          'user_id' => $availableUsers[$index]
        ]);

        // unset($availableUsers[$index]);
        array_splice($availableUsers, $index, 1);
      }
    });

    $this->enableForeignKeyChecks();
  }
}

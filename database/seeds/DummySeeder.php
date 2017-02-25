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
    foreach(['user', 'admin'] as $role){
      DB::table('roles')->insert([
        'role'  => $role
      ]);
    }

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

      DB::table('user_roles')->insert([
        'user_id' => $u->id,
        'role_id' => 0
      ]);
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

    $this->enableForeignKeyChecks();
  }
}

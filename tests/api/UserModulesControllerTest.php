<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

use App\User;


class UserModuleRoutesTest extends TestCase
{
  use DatabaseTransactions;

  /**
   * I send a POST request to /v1/users/{id}/modules/ where
   * id is a valid user id with the parameter 'modules' 
   * containing a json string of an array of valid module id's and 
   * the server adds the given modules to the user and 
   * responds appropriately.
   *
   * (TODO add authentication)
   *
   * @return void
   */
  public function testCanAddModules(){
    $this->requestHack();
    $user = $this->getUser();

    $modules = [1,2,3,4];
    $numModules = count($modules);
    $modulesJson = json_encode($modules);
    //detach modules if they are already used, to test
    //adding them again
    $user->modules()->detach($modules);

    $this->actingAs($user)
      ->post(USERS_ROUTE . "/{$user->id}/modules", [
      'modules'  => $modulesJson
    ])->seeStatusCode(self::HTTP_OK)
      ->seeJson([
        'data'  =>  "Added $numModules module(s) to user."
      ]);
     

    foreach($modules as $module_id){
      $this->seeInDatabase('user_modules', [
        'module_id'  => $module_id,
        'user_id'  => $user->id
      ]);
    }
  }

  /**
   * I send a DELETE request to /v1/users/{id}/modules/ 
   * where id is a valid user id with the parameter 'modules' 
   * containing a json string of an array of valid module id's and 
   * the server remove the selected modules from the user and
   * responds appropriately.
   * (TODO add authentication)
   *
   * @return void
   */
  public function testCanRemoveModules(){
    $this->get('/');
    $id = 1;
    $user = User::findOrFail($id);
    $modules = $user->modules()->get();
    $modules->transform(function($module, $key){
      return $module->id;
    });
    $modulesArr = $modules->toArray();
    $modulesJson = json_encode($modulesArr);
    $numModules = count($modulesArr);

    $this->actingAs($user)->delete(USERS_ROUTE . "/$id/modules", [
      'modules'  =>  $modulesJson
    ])->seeStatusCode(self::HTTP_OK)
      ->seeJson([
        'data'  =>  "Removed $numModules module(s) from user."
      ]);
    
    //check that removed modules are not present in database
    foreach($modulesArr as $module_id){
      $this->missingFromDatabase('user_modules', [
        'module_id'  => $module_id,
        'user_id'  => $user->id
      ]);
    }
  }


  /**
   * I send a GET request to /v1/users/{id}/modules/ 
   * where id is a valid user id with
   * (TODO add authentication)
   *
   * @return void
   */
  public function testCanShowModules(){
    $this->get('/');

    $id = 1;
    $user = User::findOrFail($id);
    if($user->modules()->count() > 0){
      $this->actingAs($user)->get(USERS_ROUTE . "/$id/modules")
        ->seeStatusCode(self::HTTP_OK)
        ->seeJsonStructure([
          'data'  => [
            '*' => MODULE_FIELDS 
          ]
        ]);
    }
  }
}

<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

use App\User;

const MODULE_FIELDS = [
  'name', 'description', 'code',
  'type', 'period', 'id'
];

class UserModuleRoutesTest extends TestCase
{
  use DatabaseTransactions;


  /**
   * I send a POST request to /api/v1/users/{id}/modules/ where
   * id is a valid user id with the parameter 'modules' 
   * containing a json string of an array of valid module id's and 
   * the server adds the given modules to the user and 
   * responds appropriately.
   *
   * (TODO add authentication)
   *
   * @return void
   */
  public function testCanAddmodules(){
    $this->get('/');

    $user_id = 1;
    $modules = [1,2,3,4];
    $numModules = count($modules);
    $modulesJson = json_encode($modules);

    $this->post("/api/v1/users/$user_id/modules", [
      'modules'  => $modulesJson
    ])->seeStatusCode(self::HTTP_OK)
      ->seeJson([
        'data'  =>  "Added $numModules module(s) to user."
      ]);
     

    foreach($modules as $module_id){
      $this->seeInDatabase('user_modules', [
        'module_id'  => $module_id,
        'user_id'  => $user_id
      ]);
    }
  }

  /**
   * I send a DELETE request to /api/v1/users/{id}/modules/ 
   * where id is a valid user id with the parameter 'modules' 
   * containing a json string of an array of valid module id's and 
   * the server remove the selected modules from the user and
   * responds appropriately.
   * (TODO add authentication)
   *
   * @return void
   */
  public function testCanRemovemodules(){
    $this->get('/');
    $id = 1;
    $user = User::findOrFail($id);
    $modules = $user->modules();
    $modules->transform(function($module, $key){
      return $module->id;
    });
    $modulesArr = $modules->toArray();
    $modulesJson = json_encode($modulesArr);

    $this->delete("/api/v1/users/$id/modules", [
      'modules'  =>  $modulesJson
    ])->seeStatusCode(self::HTTP_OK)
      ->seeJson([
        'data'  =>  "Removed $nummodules module(s) from user."
      ]);
    
    //check that removed modules are not present in database
    foreach($modulesArr as $module_id){
      $this->missingFromDatabase('user_modules', [
        'module_id'  => $module_id,
        'user_id'  => $user_id
      ]);
    }
  }


  /**
   * I send a GET request to /api/v1/users/{id}/modules/ 
   * where id is a valid user id with
   * (TODO add authentication)
   *
   * @return void
   */
  public function testCanShowmodules(){
    $this->get('/');

    $id = 1;
    if(User::findOrFail($id)->modules()->count() > 0){
      $this->get("/api/v1/users/$id/modules")
        ->seeStatusCode(self::HTTP_OK)
        ->seeJsonStructure([
          'data'  => [
            '*' => MODULE_FIELDS 
          ]
        ]);
    }
  }
}

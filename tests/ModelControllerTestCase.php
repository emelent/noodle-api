<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

use App\User;


class ModelControllerTestCase extends TestCase
{

  use DatabaseTransactions;

  protected $modelClass = null; 
  protected $tableName = null;
  protected $modelRoutePrefix = null;
  protected $modelFields = [];
  protected $user = null;
  protected $admin = null;


  /**
   * Seems to fix request issue when running this with codeception
   * instead of phpunit. This just sends a get request, without
   * this, all non-GET requests bug out.
   * 
   * @return void
   */
  protected function requestHack(){
    $this->get('/');
  }

  public function getUserByRole($role){
    return User::all()->filter(function($user) use($role){
      return $user->roles()->where('role', $role)->get();
    })->first();
  }

  public function getAdminUser(){
    if(!$this->admin){
      $this->admin = $this->getUserByRole('admin');
    }
    return $this->admin;
  }

  public function getUser(){
    if(!$this->user){
      $this->user = $this->getUserByRole('user');
    }
    return $this->user;
  }
  
  /**
   * I send a GET request to /api/v1/<modelRoutePrefix>/ and the server
   * returns a list of models. (TODO add authentication)
   *
   * @return void
   */
  public function testCanShowAllModels(){
    $this->actingAs(User::findOrFail(1))->get("{$this->modelRoutePrefix}/")
      ->seeStatusCode(self::HTTP_OK)
      ->seeJsonStructure([
        'data'  => [
          '*' => $this->modelFields
        ]
      ]);
  }


  /**
   * I send a GET request to  /api/v1/<modelRoutePrefix>/{id} where {id} is a
   * valid model id and the server returns a list of models. 
   * (TODO add authentication)
   *
   * @return void
   */
  public function testCanShowModelById(){
    $id = 1;
    $this->actingAs(User::findOrFail(1))->get("{$this->modelRoutePrefix}/$id")
      ->seeStatusCode(self::HTTP_OK)
      ->seeJsonStructure([
        'data'  => $this->modelFields
      ]);
  }


  /**
   * I send a GET request to /api/v1/<modelRoutePrefix>/{id} where id is
   * an invalid model id and the server returns an error.
   *
   * @expectedException
   *
   * @return void
   */
  public function testDoesNotShowModelWithAnInvalidId(){
    $invalidId = 'invalid';
    $this->actingAs(User::findOrFail(1))->get("{$this->modelRoutePrefix}/$invalidId")
      ->seeStatusCode(self::HTTP_NOT_FOUND);
  }


  /**
   * I send a DELETE request to /api/v1/<modelRoutePrefix>/{id} where {id} is a
   * valid model id and the server deletes the model from the database.
   *
   * (TODO add authentication)
   *
   * @return void
   */
  public function testCanDeleteModel(){
    $this->requestHack();

    $id = 1;
    $this->actingAs(User::findOrFail(1))->delete("{$this->modelRoutePrefix}/$id")
      ->seeStatusCode(self::HTTP_OK);

    $this->missingFromDatabase($this->tableName, ['id' => $id]);
  }



  /**
   * I send a DELETE request to /api/v1/<modelRoutePrefix>/{id} where
   * {id} is an invalid model id and the server responds 
   * appropriately.
   *
   * (TODO add authentication)
   *
   * @return void
   */
  public function testDoesNotDeleteInvalidEvent(){
    $this->requestHack();
    $id = 'invalid';
    $this->actingAs(User::findOrFail(1))->delete("{$this->modelRoutePrefix}/$id/")
      ->seeStatusCode(self::HTTP_NOT_FOUND);
  }

  /**
   * I send a PUT request to /api/v1/<modelRoutePrefix>/{id}/ where id is a 
   * non-existing model id and the server responds with the
   * appropriate message.
   * 
   * @expectedException 
   * @return void
   */
  public function testDoesNotTryToUpdateANonExistingModel(){
    $this->requestHack();

    $invalidId = 'invalid';
    $this->actingAs(User::findOrFail(1))->put("{$this->modelRoutePrefix}/$invalidId/")
      ->seeStatusCode(self::HTTP_NOT_FOUND);
  }
}

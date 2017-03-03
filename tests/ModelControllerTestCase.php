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


  /**
   * I send a GET request to /api/v1/<modelRoutePrefix>/ and the server
   * returns a list of models. (TODO add authentication)
   *
   * @return void
   */
  public function testCanShowAllModels(){
    $this->actingAs($this->getAdminUser())->get("{$this->modelRoutePrefix}/")
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
    $this->actingAs($this->getAdminUser())->get("{$this->modelRoutePrefix}/$id")
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
    $this->actingAs($this->getAdminUser())->get("{$this->modelRoutePrefix}/$invalidId")
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
    $this->actingAs($this->getAdminUser())->delete("{$this->modelRoutePrefix}/$id")
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
    $this->actingAs($this->getAdminUser())->delete("{$this->modelRoutePrefix}/$id/")
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
    $this->actingAs($this->getAdminUser())->put("{$this->modelRoutePrefix}/$invalidId/")
      ->seeStatusCode(self::HTTP_NOT_FOUND);
  }
}

<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;


class ModelControllerTestCase extends TestCase
{

  use DatabaseTransactions;

  protected $modelClass = null; 
  protected $tableName = null;
  protected $modelRoutePrefix = null;
  protected $modelFields = [];
  
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

  /**
   * I send a GET request to /api/v1/<modelRoutePrefix>/ and the server
   * returns a list of models. (TODO add authentication)
   *
   * @return void
   */
  public function testCanShowAllModels(){
    $this->get("{$this->modelRoutePrefix}/")
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
    $this->get("{$this->modelRoutePrefix}/$id")
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
    $this->get("{$this->modelRoutePrefix}/$invalidId")
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
    $this->delete("{$this->modelRoutePrefix}/$id")
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
    $this->delete("{$this->modelRoutePrefix}/$id/")
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
    $this->put("{$this->modelRoutePrefix}/$invalidId/")
      ->seeStatusCode(self::HTTP_NOT_FOUND);
  }
}

<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use App\Module;

const MODULE_FIELDS = [
  'id', 'code','description', 
  'name', 'type', 'period'
];

const MSG_CODE_REQUIRED = '';
const MSG_TYPE_REQUIRED = '';
const MSG_DESC_REQUIRED = '';
const MSG_NAME_REQUIRED = '';
const MSG_PERIOD_REQUIRED = '';

class ModuleRoutesTest extends TestCase
{

  use DatabaseTransactions;
  //use \Codeception\Specify;


  /**
   * I send a GET request to /api/v1/modules/ and the server
   * returns a list of modules. (TODO add authentication)
   *
   * @return void
   */
  public function testCanShowAllModules(){
    $that = $this;
    $that->get('/api/v1/modules/')
      ->seeStatusCode(self::HTTP_OK)
      ->seeJsonStructure([
        'data'  => [
          '*' => MODULE_FIELDS
        ]
      ]);
  }


  /**
   * I send a GET request to /api/v1/modules/{id} where {id} is a
   * valid module id and the server returns a list of modules. 
   * (TODO add authentication)
   *
   * @return void
   */
  public function testCanShowModuleById(){
    $that = $this;
    $that->get('/api/v1/modules/1/')
      ->seeStatusCode(self::HTTP_OK)
      ->seeJsonStructure([
        'data'  => MODULE_FIELDS
      ]);
  }


  /**
   * I send a GET request to /api/v1/modules/{id} where id is
   * an invalid module id and the server returns an error.
   *
   * @expectedException
   *
   * @return void
   */
  public function testDoesNotShowModuleWithAnInvalidId(){
    $that = $this;
    $invalidModuleId = 'invalid';
    $that->get("/api/v1/modules/$invalidModuleId/")
      ->seeStatusCode(self::HTTP_NOT_FOUND);
  }

  /**
   * I send a POST request to /api/v1/modules/ with valid
   * data and the server creates a new module in the database.
   *
   * (TODO add authentication)
   *
   * @return void
   */
  public function testCanCreateANewModuleWithValidData(){
    $this->get('/');
    $code = 'MOD385';


    $this->post('/api/v1/modules/', [
      'name' => 'Modulo',
      'description' => 'Description of modulo',
      'code'  => $code,
      'period'  => 'Q1',
      'type'  => 0,
    ])->seeStatusCode(self::HTTP_CREATED)
      ->seeJson([
        'data' => "The module with code '$code' has been created."
    ]);

    //check that module is in the database
    $this->seeInDatabase('modules', [
      'code' => $code,
    ]);
  }

  /**
   * I send a POST request to /api/v1/modules/ with invalid
   * data and the server sends an appropriate response and
   * does not create a module in the database.
   *
   * @return void
   */
  public function testDoesNotCreateANewModuleWithInvalidData(){
    $this->get('/');
    $code = 'MOD352';

    $this->post('/api/v1/modules/', [
    ])->seeStatusCode(self::HTTP_UNPROCESSABLE_ENTITY)
      ->seeJson([
        'code' => [MSG_CODE_REQUIRED],
        'name' => [MSG_NAME_REQUIRED],
        'period' => [MSG_PERIOD_REQUIRED],
        'type' => [MSG_TYPE_REQUIRED],
        'description' => [MSG_DESC_REQUIRED]
      ]);

    //check that module is not in the database
    $this->missingFromDatabase('modules', [
      'code' => $code,
    ]);
  }




  /**
   * I send a PUT request to /api/v1/modules/{id}/ and 
   * the server updates the module matching the given id
   * in the database with the received data and returns 
   * the appropriate response.
   * 
   * @return void
   */
  public function testCanUpdateExistingModuleWithValidData(){
    $this->get('/');
    $module = Module::findOrFail(1);
    $code = 'newModuleCode';

    $this->assertNotEquals($code, $module->code);

    //check that the api responds accordingly
    $this->put('/api/v1/modules/1/', [
      'code' => $code,
    ])->seeStatusCode(self::HTTP_OK)
      ->seeJson([
        'data' => "The module with id {$module->id} has been updated."
      ]);

    $this->seeInDatabase('modules', ['code' => $code]);
  }


  /**
   * I send a PUT request to /api/v1/modules/{id}/ and 
   * the server updates the module matching the given id
   * in the database with the received data and returns 
   * the appropriate response.
   * 
   * @return void
   */
  public function testDoesNotUpdateExistingModuleWithInvalidData(){
    $this->get('/');
    $module = Module::findOrFail(1);
    $code= 'newCode';

    //make sure test code and current code aren't the same
    $this->assertNotEquals($invalidCode, $module->code);

    //check that the api responds accordingly
    $this->put('/api/v1/modules/1/', [
      'code' => $code,
      'password' => 'short'
    ])->seeStatusCode(self::HTTP_UNPROCESSABLE_ENTITY)
      ->seeJson([
        'password' => [MSG_INVALID_PASSWORD],
        'code' => [MSG_INVALID_code],
      ]);

    $this->missingFromDatabase('modules', ['code' => $invalidcode]);
  }



  /**
   * I send a PUT request to /api/v1/modules/{id}/ where id is a 
   * non-existing module id and the server responds with the
   * appropriate message.
   * 
   * @expectedException 
   * @return void
   */
  public function testDoesNotTryToUpdateANonExistingModule(){
    $this->get('/');
    $invalidModuleId = 'invalid';

    //check that the api responds accordingly
    $this->put("/api/v1/modules/$invalidModuleId/", [
      'code' => 'new code',
    ])->seeStatusCode(self::HTTP_NOT_FOUND);
  }
}

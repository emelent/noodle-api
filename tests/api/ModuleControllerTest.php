<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use App\Module;


const MSG_CODE_REQUIRED = '';
const MSG_POSTGRAD_REQUIRED = '';
const MSG_DESC_REQUIRED = '';
const MSG_NAME_REQUIRED = '';
const MSG_PERIOD_REQUIRED = '';

class ModuleControllerTest extends ModelControllerTestCase
{

  protected $tableName = 'modules';
  protected $modelClass = Module::class;
  protected $modelRoutePrefix = MODULES_ROUTE;
  protected $modelFields = MODULE_FIELDS;

  /**
   * I send a POST request to /v1/modules/ with valid
   * data and the server creates a new module in the database.
   *
   * (TODO add authentication)
   *
   * @return void
   */
  public function testCanCreateANewModuleWithValidData(){
    $this->requestHack();

    $code = 'MOD385';
    $this->actingAs($this->getAdminUser())->post("{$this->modelRoutePrefix}/", [
      'name' => 'Modulo',
      'description' => 'Description of modulo',
      'code'  => $code,
      'period'  => 'Q1',
      'postgrad'  => 0,
    ])->seeStatusCode(self::HTTP_CREATED)
      ->seeJson([
        'data' => 'The module has been created.'
    ]);

    //check that module is in the database
    $this->seeInDatabase($this->tableName, [
      'code' => $code,
    ]);
  }
  

  /**
   * I send a POST request to /v1/modules/ with invalid
   * data and the server sends an appropriate response and
   * does not create a module in the database.
   *
   * @return void
   */
  public function testDoesNotCreateANewModuleWithInvalidData(){
    $this->requestHack();
    $code = 'MOD352';

    $this->actingAs($this->getAdminUser())->post("{$this->modelRoutePrefix}/", [
    ])->seeStatusCode(self::HTTP_UNPROCESSABLE_ENTITY);
      // ->seeJson([
      //   'code' => [MSG_CODE_REQUIRED],
      //   'name' => [MSG_NAME_REQUIRED],
      //   'period' => [MSG_PERIOD_REQUIRED],
      //   'postgrad' => [MSG_POSTGRAD_REQUIRED],
      //   'description' => [MSG_DESC_REQUIRED]
      // ]);

    //check that module is not in the database
    $this->missingFromDatabase($this->tableName, [
      'code' => $code,
    ]);
  }


  /**
   * I send a PUT request to /v1/modules/{id}/ and 
   * the server updates the module matching the given id
   * in the database with the received data and returns 
   * the appropriate response.
   * 
   * @return void
   */
  public function testCanUpdateExistingModuleWithValidData(){
    $id = 1;
    $module = Module::findOrFail($id);
    $newCode = 'new123';
    $newDescription = 'This is the new description';

    $this->assertNotEquals($newCode, $module->code);
    $this->assertNotEquals($newDescription, $module->description);

    $this->actingAs($this->getAdminUser())->put("{$this->modelRoutePrefix}/$id", [
      'code'  => $newCode,
      'description' => $newDescription
    ])->seeStatusCode(self::HTTP_OK);

    //check that module is in the database
    $this->seeInDatabase($this->tableName, [
      'code' => $newCode,
      'description' => $newDescription
    ]);
  }


  /**
   * I send a PUT request to /v1/modules/{id}/ and 
   * the server updates the module matching the given id
   * in the database with the received data and returns 
   * the appropriate response.
   * 
   * @return void
   */
  public function testDoesNotUpdateExistingModuleWithInvalidData(){
    $id = 1;
    $module = Module::findOrFail($id);
    $newCode = 'invalid code';

    $this->actingAs($this->getAdminUser())->put("{$this->modelRoutePrefix}/$id", [
      'code'  => $newCode
    ])->seeStatusCode(self::HTTP_UNPROCESSABLE_ENTITY);
      // ->seeJson([
      //   'code' => [MSG_CODE_REQUIRED],
      //   'name' => [MSG_NAME_REQUIRED],
      //   'period' => [MSG_PERIOD_REQUIRED],
      //   'postgrad' => [MSG_POSTGRAD_REQUIRED],
      //   'description' => [MSG_DESC_REQUIRED]
      // ]);

    //check that module is not in the database
    $this->missingFromDatabase($this->tableName, [
      'code' => $newCode
    ]);
  }
}

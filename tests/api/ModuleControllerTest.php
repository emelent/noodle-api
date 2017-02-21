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

class ModuleControllerTest extends ModelControllerTestCase
{

  protected $tableName = 'modules';
  protected $modelClass = Module::class;
  protected $modelRoutePrefix = '/api/v1/modules';
  protected $modelFields = [
    'id', 'code','description', 
    'name', 'type', 'period'
  ];

  /**
   * I send a POST request to /api/v1/modules/ with valid
   * data and the server creates a new module in the database.
   *
   * (TODO add authentication)
   *
   * @return void
   */
  public function testCanCreateANewModuleWithValidData(){
    $this->requestHack();

    $code = 'MOD385';
    $this->post("{$this->modelRoutePrefix}/", [
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
    $this->seeInDatabase($this->tableName, [
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

    $this->post("{$this->modelRoutePrefix}/", [
    ])->seeStatusCode(self::HTTP_UNPROCESSABLE_ENTITY)
      ->seeJson([
        'code' => [MSG_CODE_REQUIRED],
        'name' => [MSG_NAME_REQUIRED],
        'period' => [MSG_PERIOD_REQUIRED],
        'type' => [MSG_TYPE_REQUIRED],
        'description' => [MSG_DESC_REQUIRED]
      ]);

    //check that module is not in the database
    $this->missingFromDatabase($this->tableName, [
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
    $this->markTestIncomplete(
      'This test has not been implemented yet.'
    );
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
    //$this->get('/');
    $this->markTestIncomplete(
      'This test has not been implemented yet.'
    );
  }
}

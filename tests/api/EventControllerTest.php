<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use App\Event;

const MSG_DAY_REQUIRED      = '';
const MSG_START_REQUIRED    = '';
const MSG_END_REQUIRED      = '';
const MSG_DATE_REQUIRED     = '';
const MSG_GROUP_REQUIRED    = '';
const MSG_CREATOR_REQUIRED  = '';
const MSG_MODULE_REQUIRED   = '';


class EventControllerTest extends ModelControllerTestCase
{

  protected $tableName = 'events';
  protected $modelClass = Event::class;
  protected $modelRoutePrefix = '/v1/events';
  protected $modelFields = [
    'name', 'day', 'start', 'end',
    'date', 'language', 'group',
    'creator_id', 'module_id', 'created_at',
    'updated_at'
  ];


  /**
   * I send a POST request to /v1/events/ with valid
   * data and the server creates a new event in the database.
   *
   *
   * @return void
   */
  public function testCanCreateANewEventWithValidData(){
    $this->requestHack();

    $name = 'Lesson 5';
    $group = null;
    $module_id = null;
    
    $this->post("{$this->modelRoutePrefix}/", [
      'name' => $name,
      'day'  => 1,
      'start' => date("Y-m-d H:i:s"),
      'end'   =>  date("Y-m-d H:i:s", strtotime('+1 hours')),
      'date'  => null,
      'group' => $group,
      'module_id' => $module_id
    ])->seeStatusCode(self::HTTP_CREATED)
      ->seeJson([
        'data' => "The event with code '$name' has been created."
    ]);

    //check that event is in the database
    $this->seeInDatabase("{$this->tableName}", [
      'name' => $name,
      'group' => $group,
      'module_id' => $module_id
    ]);
  }


  /**
   * I send a POST request to /v1/events/ with invalid
   * data and the server sends an appropriate response and
   * does not create a event in the database.
   *
   * @return void
   */
  public function testDoesNotCreateANewEventWithInvalidData(){
    $this->get('/');
    $name = 'invalid';
    $this->post("{$this->modelRoutePrefix}/", [
    ])->seeStatusCode(self::HTTP_UNPROCESSABLE_ENTITY)
      ->seeJson([
        'day'         =>  [MSG_DAY_REQUIRED],
        'start'       =>  [MSG_START_REQUIRED],
        'end'         =>  [MSG_END_REQUIRED], 
        'date'        =>  [MSG_DATE_REQUIRED],
        'group'       =>  [MSG_GROUP_REQUIRED],
        'creator_id'  =>  [MSG_CREATOR_REQUIRED],
        'module_id'   =>  [MSG_MODULE_REQUIRED]
      ]);

    //check that event is not in the database
    $this->missingFromDatabase("{$this->tableName}", [
      'name' => $name,
    ]);
  }


  /**
   * I send a PUT request to /v1/events/{id}/ and 
   * the server updates the event matching the given id
   * in the database with the received data and returns 
   * the appropriate response.
   * 
   * @return void
   */
  public function testCanUpdateExistingEventWithValidData(){
    $this->markTestIncomplete(
      'This test has not been implemented yet.'
    );
  }


  /**
   * I send a PUT request to /v1/events/{id}/ and 
   * the server updates the event matching the given id
   * in the database with the received data and returns 
   * the appropriate response.
   * 
   * @return void
   */
  public function testDoesNotUpdateExistingEventWithInvalidData(){
    $this->markTestIncomplete(
      'This test has not been implemented yet.'
    );
  }
}

<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use App\Event;
use App\User;

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
  protected $modelRoutePrefix = EVENTS_ROUTE;
  protected $modelFields = EVENT_FIELDS;


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
    $group = 1;
    $module_id = 2;
    $language = 1;

    $this->actingAs($this->getUser())->post("{$this->modelRoutePrefix}/", [
      'name' => $name,
      'day'  => 1,
      'start' => date("H:i"),
      'end'   =>  date("H:i", strtotime('+1 hours')),
      'group' => $group,
      'language'  => $language,
      'module_id' => $module_id
    ])->seeStatusCode(self::HTTP_CREATED)
      ->seeJson([
        'data' => "The event has been created."
    ]);

    //check that event is in the database
    $this->seeInDatabase("{$this->tableName}", [
      'name' => $name,
      'group' => $group,
      'module_id' => $module_id,
      'language'  => $language
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
    $this->actingAs($this->getUser())->post("{$this->modelRoutePrefix}/", [
    ])->seeStatusCode(self::HTTP_UNPROCESSABLE_ENTITY);
      // ->seeJson([
      //   'day'         =>  [MSG_DAY_REQUIRED],
      //   'start'       =>  [MSG_START_REQUIRED],
      //   'end'         =>  [MSG_END_REQUIRED], 
      //   'date'        =>  [MSG_DATE_REQUIRED],
      //   'group'       =>  [MSG_GROUP_REQUIRED],
      //   'creator_id'  =>  [MSG_CREATOR_REQUIRED],
      //   'module_id'   =>  [MSG_MODULE_REQUIRED]
      // ]);

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
    $event = Event::findOrFail(1);
    $newName = 'new event name';
    $start = '01:23';
    $this->assertNotEquals($newName, $event->name);

    $this->actingAs($this->getAdminUser())
      ->put("{$this->modelRoutePrefix}/1/", ['name' => $newName, 'start' => $start])
      ->seeStatusCode(self::HTTP_OK)
      ->seeJson([
        'data' => 'The event has been updated.'
    ]);

    $this->seeInDatabase($this->tableName, [
      'name'  => $newName,
      'start' => $start
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
  public function testDoesNotUpdateExistingEventWithInvalidData(){
    $event = Event::findOrFail(1);
    $newName = 'invalid event';
    $start = '32101:23';
    $this->assertNotEquals($newName, $event->name);

    $this->actingAs($this->getAdminUser())
      ->put("{$this->modelRoutePrefix}/1/", ['name' => $newName, 'start' => $start])
      ->seeStatusCode(self::HTTP_UNPROCESSABLE_ENTITY);

    $this->missingFromDatabase($this->tableName, [
      'name'  => $newName,
      'start' => $start
    ]);
  }
}

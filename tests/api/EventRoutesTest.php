<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use App\Event;

const EVENT_FIELDS = [
  'name', 'day', 'start', 'end',
  'date', 'language', 'group',
  'creator_id', 'module_id', 'created_at',
  'updated_at'
];

const MSG_DAY_REQUIRED      = '';
const MSG_START_REQUIRED    = '';
const MSG_END_REQUIRED      = '';
const MSG_DATE_REQUIRED     = '';
const MSG_GROUP_REQUIRED    = '';
const MSG_CREATOR_REQUIRED  = '';
const MSG_MODULE_REQUIRED   = '';


class EventRoutesTest extends TestCase
{

  use DatabaseTransactions;


  /**
   * I send a GET request to /api/v1/events/ and the server
   * returns a list of events. (TODO add authentication)
   *
   * @return void
   */
  public function testCanShowAllEvents(){
    $that = $this;
    $that->get('/api/v1/events/')
      ->seeStatusCode(self::HTTP_OK)
      ->seeJsonStructure([
        'data'  => [
          '*' => EVENT_FIELDS
        ]
      ]);
  }


  /**
   * I send a GET request to /api/v1/events/{id} where {id} is a
   * valid event id and the server returns a list of events. 
   * (TODO add authentication)
   *
   * @return void
   */
  public function testCanShowEventById(){
    $that = $this;
    $that->get('/api/v1/events/1/')
      ->seeStatusCode(self::HTTP_OK)
      ->seeJsonStructure([
        'data'  => EVENT_FIELDS
      ]);
  }


  /**
   * I send a GET request to /api/v1/events/{id} where id is
   * an invalid event id and the server returns an error.
   *
   * @expectedException
   *
   * @return void
   */
  public function testDoesNotShowEventWithAnInvalidId(){
    $that = $this;
    $invalidEventId = 'invalid';
    $that->get("/api/v1/events/$invalidEventId/")
      ->seeStatusCode(self::HTTP_NOT_FOUND);
  }

  /**
   * I send a POST request to /api/v1/events/ with valid
   * data and the server creates a new event in the database.
   *
   * (TODO add authentication)
   *
   * @return void
   */
  public function testCanCreateANewEventWithValidData(){
    $this->get('/');
    $name = 'Lesson 5';
    $group = null;
    $module_id = null;

    
    $this->post('/api/v1/events/', [
      'name' => $name,
      'day'  => 1,
      'start' => date("Y-m-d H:i:s"),
      'end'   =>  date("Y-m-d H:i:s", strtotime('+1 hours')),
      'date'  => null,
      'group' => $group,
      'creator_id'  => null,
      'module_id' => $module_id
    ])->seeStatusCode(self::HTTP_CREATED)
      ->seeJson([
        'data' => "The event with code '$name' has been created."
    ]);

    //check that event is in the database
    $this->seeInDatabase('events', [
      'name' => $name,
      'group' => $group,
      'module_id' => $module_id
    ]);
  }

  /**
   * I send a POST request to /api/v1/events/ with invalid
   * data and the server sends an appropriate response and
   * does not create a event in the database.
   *
   * @return void
   */
  public function testDoesNotCreateANewEventWithInvalidData(){
    $this->get('/');
    $name = 'invalid';
    $this->post('/api/v1/events/', [
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
    $this->missingFromDatabase('events', [
      'name' => $name,
    ]);
  }




  /**
   * I send a PUT request to /api/v1/events/{id}/ and 
   * the server updates the event matching the given id
   * in the database with the received data and returns 
   * the appropriate response.
   * 
   * @return void
   */
  public function testCanUpdateExistingEventWithValidData(){
    $this->get('/');
    $event = Event::findOrFail(1);
    $newName = 'newEventName';

    $this->assertNotEquals($newName, $event->name);

    //check that the api responds accordingly
    $this->put('/api/v1/events/1/', [
      'name' => $newName,
    ])->seeStatusCode(self::HTTP_OK)
      ->seeJson([
        'data' => "The event with id {$event->id} has been updated."
      ]);

    $this->seeInDatabase('events', ['newName' => $newName]);
  }


  /**
   * I send a PUT request to /api/v1/events/{id}/ and 
   * the server updates the event matching the given id
   * in the database with the received data and returns 
   * the appropriate response.
   * 
   * @return void
   */
  public function testDoesNotUpdateExistingEventWithInvalidData(){
    $this->get('/');
  }



  /**
   * I send a PUT request to /api/v1/events/{id}/ where id is a 
   * non-existing event id and the server responds with the
   * appropriate message.
   * 
   * @expectedException 
   * @return void
   */
  public function testDoesNotTryToUpdateANonExistingEvent(){
    $this->get('/');
    $invalidEventId = 'invalid';

    //check that the api responds accordingly
    $this->put("/api/v1/events/$invalidEventId/", [
      'name' => 'newName',
    ])->seeStatusCode(self::HTTP_NOT_FOUND);
  }

  /**
   * I send a DELETE request to /api/v1/events/{id} where {id} is a
   * valid event id and the server deletes the event from the database.
   *
   * (TODO add authentication)
   *
   * @return void
   */
  public function testCanDeleteevent(){
    $that = $this;
    $id = 1;
    $that->delete("/api/v1/events/$id/")
      ->seeStatusCode(self::HTTP_OK);

    $this->missingFromDatabase('events', ['id' => $id]);
  }

  /**
   * I send a DELETE request to /api/v1/events/{id} where {id} is an
   * invalid event id and the server responds appropriately.
   *
   * (TODO add authentication)
   *
   * @return void
   */
  public function testDoesNotDeleteInvalidevent(){
    $that = $this;
    $id = 'invalid';
    $that->delete("/api/v1/events/$id/")
      ->seeStatusCode(self::HTTP_NOT_FOUND);
  }
}

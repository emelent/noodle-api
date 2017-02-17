<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use App\Timetable;

const TIMETABLE_FIELDS = [
  'id', 'hash', 'creator_id',
  'created_at', 'updated_at'
];

class TimetableRoutesTest extends TestCase
{

  use DatabaseTransactions;


  /**
   * I send a GET request to /api/v1/timetables/ and the server
   * returns a list of timetables. (TODO add authentication)
   *
   * @return void
   */
  public function testCanShowAllTimetables(){
    $that = $this;
    $that->get('/api/v1/timetables/')
      ->seeStatusCode(self::HTTP_OK)
      ->seeJsonStructure([
        'data'  => [
          '*' => TIMETABLE_FIELDS
        ]
      ]);
  }


  /**
   * I send a GET request to /api/v1/timetables/{id} where {id} is a
   * valid timetable id and the server returns a list of timetables. 
   * (TODO add authentication)
   *
   * @return void
   */
  public function testCanShowTimetableById(){
    $that = $this;
    $that->get('/api/v1/timetables/1/')
      ->seeStatusCode(self::HTTP_OK)
      ->seeJsonStructure([
        'data'  => TIMETABLE_FIELDS
      ]);
  }


  /**
   * I send a GET request to /api/v1/timetables/{id} where id is
   * an invalid timetable id and the server returns an error.
   *
   * @expectedException
   *
   * @return void
   */
  public function testDoesNotShowTimetableWithAnInvalidId(){
    $that = $this;
    $invalidTimetableId = 'invalid';
    $that->get("/api/v1/timetables/$invalidTimetableId/")
      ->seeStatusCode(self::HTTP_NOT_FOUND);
  }

  /**
   * I send a POST request to /api/v1/timetables/ with valid
   * data and the server creates a new timetable in the database.
   *
   * (TODO add authentication)
   *
   * @return void
   */
  public function testCanCreateANewTimetableWithValidData(){
    $this->get('/');
    $name = 'new timetable';
    $creator_id = 'id';

    $this->post('/api/v1/timetables/', [
      'name' => 'Modulo',
      'description' => 'Description of modulo',
      'code'  => $code,
      'period'  => 'Q1',
      'type'  => 0,
    ])->seeStatusCode(self::HTTP_CREATED)
      ->seeJson([
        'data' => "The timetable with code '$code' has been created."
    ]);

    //check that timetable is in the database
    $this->seeInDatabase('timetables', [
      'code' => $code,
    ]);
  }

  /**
   * I send a POST request to /api/v1/timetables/ with invalid
   * data and the server sends an appropriate response and
   * does not create a timetable in the database.
   *
   * @return void
   */
  public function testDoesNotCreateANewTimetableWithInvalidData(){
    $this->get('/');
    $code = 'MOD352';

    $this->post('/api/v1/timetables/', [
    ])->seeStatusCode(self::HTTP_UNPROCESSABLE_ENTITY)
      ->seeJson([
        'code' => [MSG_CODE_REQUIRED],
        'name' => [MSG_NAME_REQUIRED],
        'period' => [MSG_PERIOD_REQUIRED],
        'type' => [MSG_TYPE_REQUIRED],
        'description' => [MSG_DESC_REQUIRED]
      ]);

    //check that timetable is not in the database
    $this->missingFromDatabase('timetables', [
      'code' => $code,
    ]);
  }




  /**
   * I send a PUT request to /api/v1/timetables/{id}/ and 
   * the server updates the timetable matching the given id
   * in the database with the received data and returns 
   * the appropriate response.
   * 
   * @return void
   */
  public function testCanUpdateExistingTimetableWithValidData(){
    $this->get('/');
    $timetable = Timetable::findOrFail(1);
    $code = 'newTimetableCode';

    $this->assertNotEquals($code, $timetable->code);

    //check that the api responds accordingly
    $this->put('/api/v1/timetables/1/', [
      'code' => $code,
    ])->seeStatusCode(self::HTTP_OK)
      ->seeJson([
        'data' => "The timetable with id {$timetable->id} has been updated."
      ]);

    $this->seeInDatabase('timetables', ['code' => $code]);
  }


  /**
   * I send a PUT request to /api/v1/timetables/{id}/ and 
   * the server updates the timetable matching the given id
   * in the database with the received data and returns 
   * the appropriate response.
   * 
   * @return void
   */
  public function testDoesNotUpdateExistingTimetableWithInvalidData(){
    $this->get('/');
    $timetable = Timetable::findOrFail(1);
    $code= 'newCode';

    //make sure test code and current code aren't the same
    $this->assertNotEquals($invalidCode, $timetable->code);

    //check that the api responds accordingly
    $this->put('/api/v1/timetables/1/', [
      'code' => $code,
      'password' => 'short'
    ])->seeStatusCode(self::HTTP_UNPROCESSABLE_ENTITY)
      ->seeJson([
        'password' => [MSG_INVALID_PASSWORD],
        'code' => [MSG_INVALID_code],
      ]);

    $this->missingFromDatabase('timetables', ['code' => $invalidcode]);
  }



  /**
   * I send a PUT request to /api/v1/timetables/{id}/ where id is a 
   * non-existing timetable id and the server responds with the
   * appropriate message.
   * 
   * @expectedException 
   * @return void
   */
  public function testDoesNotTryToUpdateANonExistingTimetable(){
    $this->get('/');
    $invalidTimetableId = 'invalid';

    //check that the api responds accordingly
    $this->put("/api/v1/timetables/$invalidTimetableId/", [
      'code' => 'new code',
    ])->seeStatusCode(self::HTTP_NOT_FOUND);
  }

  /**
   * I send a DELETE request to /api/v1/timetables/{id} where {id} is a
   * valid timetable id and the server deletes the timetable from the database.
   *
   * (TODO add authentication)
   *
   * @return void
   */
  public function testCanDeletetimetable(){
    $that = $this;
    $id = 1;
    $that->delete("/api/v1/timetables/$id/")
      ->seeStatusCode(self::HTTP_OK);

    $this->missingFromDatabase('timetables', ['id' => $id]);
  }

  /**
   * I send a DELETE request to /api/v1/timetables/{id} where {id} is an
   * invalid timetable id and the server responds appropriately.
   *
   * (TODO add authentication)
   *
   * @return void
   */
  public function testDoesNotDeleteInvalidtimetable(){
    $that = $this;
    $id = 'invalid';
    $that->delete("/api/v1/timetables/$id/")
      ->seeStatusCode(self::HTTP_NOT_FOUND);
  }
}

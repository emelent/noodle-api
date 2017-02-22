<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use App\Timetable;


class TimetableRoutesTest extends ModelControllerTestCase
{

  protected $modelClass = Timetable::class; 
  protected $tableName = 'timetables';
  protected $modelRoutePrefix = '/v1/timetables';
  protected $modelFields = [
    'id', 'hash', 'creator_id',
    'created_at', 'updated_at'
  ];


  /**
   * I send a POST request to /v1/timetables/ with valid
   * data and the server creates a new timetable in the database.
   *
   * (TODO add authentication)
   *
   * @return void
   */
  public function testCanCreateANewTimetableWithValidData(){
    //$this->requestHack();
    $this->markTestIncomplete(
      'This test has not been implemented yet.'
    );
  }


  /**
   * I send a POST request to /v1/timetables/ with invalid
   * data and the server sends an appropriate response and
   * does not create a timetable in the database.
   *
   * @return void
   */
  public function testDoesNotCreateANewTimetableWithInvalidData(){
    //$this->requestHack();
    $this->markTestIncomplete(
      'This test has not been implemented yet.'
    );
  }


  /**
   * I send a PUT request to /v1/timetables/{id}/ and 
   * the server updates the timetable matching the given id
   * in the database with the received data and returns 
   * the appropriate response.
   * 
   * @return void
   */
  public function testCanUpdateExistingTimetableWithValidData(){
    $this->markTestIncomplete(
      'This test has not been implemented yet.'
    );
  }


  /**
   * I send a PUT request to /v1/timetables/{id}/ and 
   * the server updates the timetable matching the given id
   * in the database with the received data and returns 
   * the appropriate response.
   * 
   * @return void
   */
  public function testDoesNotUpdateExistingTimetableWithInvalidData(){
    $this->markTestIncomplete(
      'This test has not been implemented yet.'
    );
  }
}

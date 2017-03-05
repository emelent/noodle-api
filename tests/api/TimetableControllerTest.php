<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use App\Timetable;


class TimetableRoutesTest extends ModelControllerTestCase
{

  protected $modelClass = Timetable::class; 
  protected $tableName = 'timetables';
  protected $modelRoutePrefix = TIMETABLES_ROUTE;
  protected $modelFields = TIMETABLE_FIELDS;


  /**
   * I send a POST request to /v1/timetables/ with valid
   * data and the server creates a new timetable in the database.
   *
   * (TODO add authentication)
   *
   * @return void
   */
  public function testCanCreateANewTimetableWithValidData(){
    $this->requestHack();
    $this->actingAs($this->getUser())
      ->post("{$this->modelRoutePrefix}/")
      ->seeStatusCode(self::HTTP_CREATED)
      ->seeJson([
        'data'  => 'The timetable has been created.']);

    $this->seeInDatabase($this->tableName, [
      'creator_id'  => $this->getUser()->id
    ]);
  }


  /**
   * @override
   */
  public function testDoesNotTryToUpdateANonExistingModel(){
  }
}

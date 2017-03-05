<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;


class NoAuthUserTimetablesControllerTest extends NoAuthModelControllerTestCase
{

  protected $modelRoutePrefix = USERS_ROUTE . '/1/timetables/';
  protected $modelFields = TIMETABLE_FIELDS;

  public function testCanNotShowTimetables()
  {
    $this->canNotShowAllModels();
  }

  public function testCanNotAddTimetables()
  {
    $this->requestHack();
    $this->post($this->modelRoutePrefix, ['timetables' => '[1,2,3]'])
      ->seeStatusCode(self::HTTP_UNAUTHORIZED);
  }

  public function testCanNotRemoveTimetables()
  {
    $this->requestHack();
    $this->delete($this->modelRoutePrefix, ['timetables' => '[1,2,3]'])
      ->seeStatusCode(self::HTTP_UNAUTHORIZED);
  }
}

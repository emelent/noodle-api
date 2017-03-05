<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;


class NoAuthTimetableEventsControllerTest extends NoAuthModelControllerTestCase
{

  protected $modelRoutePrefix = TIMETABLES_ROUTE . '/1/events/';
  protected $modelFields = EVENT_FIELDS;

  public function testCanShowEvents()
  {
    $this->canShowAllModels();
  }

  public function testCanNotAddEvents()
  {
    $this->requestHack();
    $this->post($this->modelRoutePrefix, ['events' => '[1,2,3]'])
      ->seeStatusCode(self::HTTP_UNAUTHORIZED);
  }

  public function testCanNotRemoveEvents()
  {
    $this->requestHack();
    $this->delete($this->modelRoutePrefix, ['events' => '[1,2,3]'])
      ->seeStatusCode(self::HTTP_UNAUTHORIZED);
  }
}

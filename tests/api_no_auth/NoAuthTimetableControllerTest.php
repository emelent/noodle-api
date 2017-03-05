<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use App\Timetable;

class NoAuthTimetableControllerTest extends NoAuthModelControllerTestCase
{

  protected $modelRoutePrefix = TIMETABLES_ROUTE;
  protected $modelFields = TIMETABLE_FIELDS;
  public function testCanShowAllTimetables()
  {
    $this->canShowAllModels();
  }

  public function testCanShowTimetableById()
  {
    $this->canShowModelById();
  }


  public function testCanNotDeleteTimetable()
  {
    $this->canNotDeleteModel();
  }

  public function testCanNotCreateNewTimetable()
  {
    $this->canNotCreateNewModel();
  }
}

<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use App\Timetable;

class TimetableControllerTest extends NoAuthModelControllerTestCase
{

  protected $modelRoutePrefix = '/v1/timetables';
  protected $modelFields = [
    'id', 'hash', 'creator_id',
    'created_at', 'updated_at'
  ];

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

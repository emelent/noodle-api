<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use App\Event;

class EventControllerTest extends NoAuthModelControllerTestCase
{

  protected $modelRoutePrefix = '/v1/events';
  protected $modelFields = [
    'name', 'day', 'start', 'end',
    'date', 'language', 'group',
    'creator_id', 'module_id', 'created_at',
    'updated_at'
  ];

  public function testDoesNotShowAllEvents()
  {
    $this->canNotShowAllModels();
  }

  public function testDoesNotShowEventById()
  {
    $this->canNotShowModelById();
  }
}

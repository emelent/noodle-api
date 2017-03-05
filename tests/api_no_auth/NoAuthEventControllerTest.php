<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use App\Event;

class NoAuthEventControllerTest extends NoAuthModelControllerTestCase
{

  protected $modelRoutePrefix = EVENTS_ROUTE;
  protected $modelFields = EVENT_FIELDS;

  public function testDoesNotShowAllEvents()
  {
    $this->canNotShowAllModels();
  }

  public function testDoesNotShowEventById()
  {
    $this->canNotShowModelById();
  }

  
  public function testCanNotUpdateEvent()
  {
    $this->canNotUpdateModel();
  }

  public function testCanNotDeleteEvent()
  {
    $this->canNotDeleteModel();
  }

  public function testCanNotCreateNewEvent()
  {
    $this->canNotCreateNewModel();
  }
}

<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use App\Module;

class NoAuthModuleControllerTest extends NoAuthModelControllerTestCase
{

  protected $modelRoutePrefix = MODULES_ROUTE;
  protected $modelFields = MODULE_FIELDS;

  public function testCanShowAllModules()
  {
    $this->canShowAllModels();
  }

  public function testCanShowModuleById()
  {
    $this->canShowModelById();
  }

  public function testCanNotUpdateModule()
  {
    $this->canNotUpdateModel();
  }

  public function testCanNotDeleteModule()
  {
    $this->canNotDeleteModel();
  }

  public function testCanNotCreateNewModule()
  {
    $this->canNotCreateNewModel();
  }
}

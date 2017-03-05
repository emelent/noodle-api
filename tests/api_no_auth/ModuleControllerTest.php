<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use App\Module;

class ModuleControllerTest extends NoAuthModelControllerTestCase
{

  protected $modelRoutePrefix = '/v1/modules';
  protected $modelFields = [
    'name', 'description', 'code',
    'postgrad', 'period'
  ];

  public function testCanShowAllModules()
  {
    $this->canShowAllModels();
  }

  public function testCanShowModuleById()
  {
    $this->canShowModelById();
  }
}

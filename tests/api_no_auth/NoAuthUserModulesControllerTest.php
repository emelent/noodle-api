<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;


class NoAuthUserModulesControllerTest extends NoAuthModelControllerTestCase
{

  protected $modelRoutePrefix = USERS_ROUTE . '/1/modules/';
  protected $modelFields = MODULE_FIELDS;

  public function testCanNotShowModules()
  {
    $this->canNotShowAllModels();
  }

  public function testCanNotAddModules()
  {
    $this->requestHack();
    $this->post($this->modelRoutePrefix, ['modules' => '[1,2,3]'])
      ->seeStatusCode(self::HTTP_UNAUTHORIZED);
  }

  public function testCanNotRemoveModules()
  {
    $this->requestHack();
    $this->delete($this->modelRoutePrefix, ['modules' => '[1,2,3]'])
      ->seeStatusCode(self::HTTP_UNAUTHORIZED);
  }
}

<?php

class NoAuthModelControllerTestCase extends TestCase
{
	protected $moduleRoutePrefix;

	public function testCanNotCreateNewModel()
	{
		$this->requestHack();
		$this->post("{$this->moduleRoutePrefix}/")
			->seeStatusCode(self::HTTP_UNAUTHORIZED);
	}
	public function testCanNotUpdateExistingModel()
	{
		$this->requestHack();
		$this->put("{$this->moduleRoutePrefix}/1/")
			->seeStatusCode(self::HTTP_UNAUTHORIZED);
	}
	
	public function testCanNotDeleteModel()
	{
		$this->requestHack();
		$this->delete("{$this->moduleRoutePrefix}/1/")
			->seeStatusCode(self::HTTP_UNAUTHORIZED);
	}
}
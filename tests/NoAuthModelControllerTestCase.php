<?php

class NoAuthModelControllerTestCase extends TestCase
{
	protected $moduleRoutePrefix;
	protected $modelFields;

  /**
   * I send a GET request to /api/v1/<modelRoutePrefix>/ and the server
   * returns a list of models.
   *
   * @return void
   */
  public function canShowAllModels(){
    $this->get("{$this->modelRoutePrefix}/")
      ->seeStatusCode(self::HTTP_OK)
      ->seeJsonStructure([
        'data'  => [
          '*' => $this->modelFields
        ]
      ]);
  }


  /**
   * I send a GET request to  /api/v1/<modelRoutePrefix>/{id} where {id} is a
   * valid model id and the server returns a list of models. 
   * 
   *
   * @return void
   */
  public function canShowModelById(){
    $id = 1;
    $this->get("{$this->modelRoutePrefix}/$id")
      ->seeStatusCode(self::HTTP_OK)
      ->seeJsonStructure([
        'data'  => $this->modelFields
      ]);
  }

  /**
   * I send a GET request to /api/v1/<modelRoutePrefix>/ and the server
   * returns a list of models. (TODO add authentication)
   *
   * @return void
   */
  public function canNotShowAllModels(){
    $this->get("{$this->modelRoutePrefix}/")
      ->seeStatusCode(self::HTTP_UNAUTHORIZED);
  }


  /**
   * I send a GET request to  /api/v1/<modelRoutePrefix>/{id} where {id} is a
   * valid model id and the server returns a list of models. 
   * (TODO add authentication)
   *
   * @return void
   */
  public function canNotShowModelById(){
    $id = 1;
    $this->get("{$this->modelRoutePrefix}/$id")
      ->seeStatusCode(self::HTTP_UNAUTHORIZED);
  }

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
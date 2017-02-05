<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use App\User;

class ExampleTest extends TestCase
{

  use DatabaseTransactions;

  /**
   * A basic test example.
   *
   * @return void
   */
  public function testExample()
  {
    $this->get('/')
      ->seeStatusCode(self::HTTP_OK);

    $this->assertEquals(
        $this->app->version(), $this->response->getContent()
    );
    $email = 'doma@gmail.com';
		$this->post('/api/v1/users/', [
      'email' => $email,
      'password'=> 'dumouymy'
    ])->seeStatusCode(self::HTTP_CREATED);

    $this->get('/api/v1/users/')
      ->seeJsonStructure([
        'data' => [
          [
            'email',
            'id'
          ]
        ]
      ]);

    $this->seeInDatabase('users', ['email' => $email]);
  }
}

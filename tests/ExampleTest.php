<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use App\User;

class ExampleTest extends TestCase
{
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

		$user = User::create([
      'email' => 'dummy@gmail.com',
      'password'=> 'dummy'
    ]);

    $this->seeInDatabase('users', ['email' => $user->email]);
  }
}

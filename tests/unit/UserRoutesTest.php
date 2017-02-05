<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use App\User;

class UserRoutesTest extends TestCase
{

  use DatabaseTransactions;


  /**
   * I send a GET request to /api/v1/users/ and the server
   * returns a list of users. (TODO add authentication)
   *
   * @return void
   */
  public function testShowAll(){
    $this->get('/api/v1/users/')
      ->seeStatusCode(self::HTTP_OK)
      ->seeJsonStructure([
        'data'  => [
          'email',
          'id'
        ]
      ]);
  }


  /**
   * I send a POST request to /api/v1/users/ with valid
   * data and the server creates a new user in the database.
   *
   * @return void
   */
  public function testStoreValid(){
    $email = 'doma@gmail.com';
    $password = 'password99';

    //check that the api responds accordingly
    $this->post('/api/v1/users/', [
      'email' => $email, 'password' => $password
    ])->seeStatusCode(self::HTTP_CREATED)
      ->seeJson([
        "The user with email $email has been created"
      ]);

    //check that user is really in the database
    $this->seeInDatabase('users', [
      'email' => $email,
      'password'  => Hash::make($password)
    ]);
  }


  /**
   * I send a POST request to /api/v1/users/ with an invalid
   * email and the server sends an appropriate response and
   * does not create a user in the database.
   *
   * @return void
   */
  public function testStoreInvalidEmail(){
    $email = 'doma';
    $password = 'password99';

    //check that the api responds accordingly
    $this->post('/api/v1/users/', [
      'email' => $email, 'password' => $password
    ])->seeStatusCode(self::HTTP_UNPROCESSABLE_ENTITY)
      ->seeJson([
        "The email must be a valid email address."
      ]);

    //check that user is not in the database
    $this->missingFromDatabase('users', [
      'email' => $email,
      'password'  => Hash::make($password)
    ]);
  }


  /**
   * I send a POST request to /api/v1/users/ without an
   * email and the server sends an appropriate response and
   * does not create a user in the database.
   *
   * @return void
   */
  public function testStoreWithoutEmail(){
    $password = 'password99';

    //check that the api responds accordingly
    $this->post('/api/v1/users/', [
      'password' => $password
    ])->seeStatusCode(self::HTTP_UNPROCESSABLE_ENTITY)
      ->seeJson([
        "The email field is required."
      ]);

    //check that user is not in the database
    $this->missingFromDatabase('users', [
      'password'  => Hash::make($password)
    ]);
  }
}

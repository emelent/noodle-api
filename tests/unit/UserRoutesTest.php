<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use App\User;

class UserRoutesTest extends TestCase
{

  use DatabaseTransactions;
  use \Codeception\Specify;


  /**
   * I send a GET request to /api/v1/users/ and the server
   * returns a list of users. (TODO add authentication)
   *
   * @return void
   */
  public function testShowAll(){
    $that = $this;
    $that->get('/api/v1/users/')
      ->seeStatusCode(self::HTTP_OK)
      ->seeJsonStructure([
        'data'  => [
          '*' => [
            'email',
            'id'
          ]
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
    $this->get('/');

    $email = 'doma@gmail.com';
    $password = 'password99';


    $this->post('/api/v1/users/', [
      'email' => $email,
      'password' => $password
    ])->seeStatusCode(self::HTTP_CREATED)
      ->seeJson([
        'data' => "The user with email $email has been created"
    ]);

    //check that user is in the database
    $this->seeInDatabase('users', [
      'email' => $email,
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
    $this->get('/');
    $email = 'doma';
    $password = 'password99';

    echo "email => $email";
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
    $this->get('/');
    $password = 'password99';

    //check that the api responds accordingly
    $this->post('/api/v1/users/', [
      'password' => $password
    ])->seeStatusCode(self::HTTP_UNPROCESSABLE_ENTITY)
      ->seeJson([
        "The email field is required."
      ]);
  }



  /**
   * I send a POST request to /api/v1/users/ without an
   * email and the server sends an appropriate response and
   * does not create a user in the database.
   *
   * @return void
   */
  public function testStoreWithoutPassword(){
    $this->get('/');
    $email = 'doma@gmail.com';

    //check that the api responds accordingly
    $this->post('/api/v1/users/', [
      'email' => $email
    ])->seeStatusCode(self::HTTP_UNPROCESSABLE_ENTITY)
      ->seeJson([
        "The password field is required."
      ]);
  }


  /**
   * I send a PUT request to /api/v1/users/{id}/ and 
   * the server updates the user matching the user id
   * in the database and returns the appropriate response
   * 
   * @return void
   */
  public function testUpdateExistingUser(){
    $this->get('/');
    $user = User::findOrFail(1);
    $email = 'mynewemail@extranew.com';

    $this->assertNotEquals($email, $user->email);
    //check that the api responds accordingly
    $this->put('/api/v1/users/1/', [
      'email' => $email,
      'password' => 'newPassword'
    ])->seeStatusCode(self::HTTP_OK)
      ->seeJson([
        'data' => "The user with id {$user->id} has been updated"
      ]);

    $this->seeInDatabase('users', ['email' => $email]);
  }

}

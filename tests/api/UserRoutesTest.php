<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use App\User;

const MSG_INVALID_EMAIL = 'The email must be a valid email address.';
const MSG_INVALID_PASSWORD = 'The password must be at least 6 characters.';
const MSG_EMAIL_REQUIRED = 'The email field is required.';
const MSG_PASSWORD_REQUIRED = 'The password field is required.';

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
  public function testCanShowAllUsers(){
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
   * I send a GET request to /api/v1/users/{id} where {id} is a
   * valid user id and the server returns a list of users. 
   * (TODO add authentication)
   *
   * @return void
   */
  public function testCanShowUserById(){
    $that = $this;
    $that->get('/api/v1/users/1/')
      ->seeStatusCode(self::HTTP_OK)
      ->seeJsonStructure([
        'data'  => [
          'email',
          'id'
        ]
      ]);
  }


  /**
   * I send a GET request to /api/v1/users/{id} where id is
   * an invalid user id and the server returns an error.
   *
   * @expectedException
   *
   * @return void
   */
  public function testDoesNotShowUserWithAnInvalidId(){
    $that = $this;
    $invalidUserId = 'invalid';
    $that->get("/api/v1/users/$invalidUserId/")
      ->seeStatusCode(self::HTTP_NOT_FOUND);
  }

  /**
   * I send a POST request to /api/v1/users/ with valid
   * data and the server creates a new user in the database.
   *
   * (TODO add authentication)
   *
   * @return void
   */
  public function testCanCreateANewUserWithValidData(){
    $this->get('/');

    $email = 'doma@gmail.com';
    $password = 'password99';


    $this->post('/api/v1/users/', [
      'email' => $email,
      'password' => $password
    ])->seeStatusCode(self::HTTP_CREATED)
      ->seeJson([
        'data' => "The user with email $email has been created."
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
  public function testDoesNotCreateANewUserWithAnInvalidEmail(){
    $this->get('/');
    $email = 'doma';
    $password = 'password99';

    echo "email => $email";
    //check that the api responds accordingly
    $this->post('/api/v1/users/', [
      'email' => $email, 'password' => $password
    ])->seeStatusCode(self::HTTP_UNPROCESSABLE_ENTITY)
      ->seeJson([
        MSG_INVALID_EMAIL
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
  public function testDoesNotCreateANewUserWithoutAnEmail(){
    $this->get('/');
    $password = 'password99';

    //check that the api responds accordingly
    $this->post('/api/v1/users/', [
      'password' => $password
    ])->seeStatusCode(self::HTTP_UNPROCESSABLE_ENTITY)
      ->seeJson([
        MSG_EMAIL_REQUIRED
      ]);
  }



  /**
   * I send a POST request to /api/v1/users/ without a
   * password and the server sends an appropriate response and
   * does not create a user in the database.
   *
   * @return void
   */
  public function testDoesNotCreateANewUserWithoutAPassword(){
    $this->get('/');
    $email = 'doma@gmail.com';

    //check that the api responds accordingly
    $this->post('/api/v1/users/', [
      'email' => $email
    ])->seeStatusCode(self::HTTP_UNPROCESSABLE_ENTITY)
      ->seeJson([
        MSG_PASSWORD_REQUIRED
      ]);
  }


  /**
   * I send a POST request to /api/v1/users/ without an email and
   * password and the server sends an appropriate response and
   * does not create a user in the database.
   *
   * @return void
   */
  public function testDoesNotCreateANewUserWithoutAnEmailAndPassword(){
    $this->get('/');
    $email = 'doma@gmail.com';

    //check that the api responds accordingly
    $this->post('/api/v1/users/')->seeStatusCode(self::HTTP_UNPROCESSABLE_ENTITY)
      ->seeJson([
        'email' => [MSG_EMAIL_REQUIRED],
        'password' => [MSG_PASSWORD_REQUIRED],
      ]);
  }

  /**
   * I send a POST request to /api/v1/users/ with an
   * invalid email and the server sends an appropriate response 
   * and does not create a user in the database.
   *
   * @return void
   */
  public function testDoesNotCreateANewUserWithAnInvalidPassword(){
    $this->get('/');
    $email = 'doma@gmail.com';
    $password= 'short';

    //check that the api responds accordingly
    $this->post('/api/v1/users/', [
      'email' => $email,
      'password' => $password,
    ])->seeStatusCode(self::HTTP_UNPROCESSABLE_ENTITY)
      ->seeJson([
        MSG_INVALID_PASSWORD 
      ]);
  }

  /**
   * I send a POST request to /api/v1/users/ with an
   * invalid email and an invalid password and the server sends an 
   * appropriate response and does not create a user in the database.
   *
   * @return void
   */
  public function testDoesNotCreateANewUserWithAnInvalidEmailAndPassword(){
    $this->get('/');
    $email = 'domagmail.com';
    $password= 'short';

    //check that the api responds accordingly
    $this->post('/api/v1/users/', [
      'email' => $email,
      'password' => $password,
    ])->seeStatusCode(self::HTTP_UNPROCESSABLE_ENTITY)
      ->seeJson([
        'password' => [MSG_INVALID_PASSWORD],
        'email' => [MSG_INVALID_EMAIL],
      ]);
  }



  /**
   * I send a PUT request to /api/v1/users/{id}/ and 
   * the server updates the user matching the given id
   * in the database with the received data and returns 
   * the appropriate response.
   * 
   * @return void
   */
  public function testCan_updateExistingUserWithValidData(){
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
        'data' => "The user with id {$user->id} has been updated."
      ]);

    $this->seeInDatabase('users', ['email' => $email]);
  }


  /**
   * I send a PUT request to /api/v1/users/{id}/ and 
   * the server updates the user matching the given id
   * in the database with the received data and returns 
   * the appropriate response.
   * 
   * @return void
   */
  public function testDoesNotUpdateExistingUserWithInvalidData(){
    $this->get('/');
    $user = User::findOrFail(1);
    $invalidEmail = 'invalid';

    //make sure test email and current email aren't the same
    $this->assertNotEquals($invalidEmail, $user->email);

    //check that the api responds accordingly
    $this->put('/api/v1/users/1/', [
      'email' => $invalidEmail,
      'password' => 'short'
    ])->seeStatusCode(self::HTTP_UNPROCESSABLE_ENTITY)
      ->seeJson([
        'password' => [MSG_INVALID_PASSWORD],
        'email' => [MSG_INVALID_EMAIL],
      ]);

    $this->missingFromDatabase('users', ['email' => $invalidEmail]);
  }



  /**
   * I send a PUT request to /api/v1/users/{id}/ where id is a 
   * non-existing user id and the server responds with the
   * appropriate message.
   * 
   * @expectedException 
   * @return void
   */
  public function testDoesNotTryToUpdateANonExistingUser(){
    $this->get('/');
    $invalidUserId = 'invalid';

    //check that the api responds accordingly
    $this->put("/api/v1/users/$invalidUserId/", [
      'email' => 'new email',
    ])->seeStatusCode(self::HTTP_NOT_FOUND);
  }
}

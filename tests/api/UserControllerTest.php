<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use App\User;

const MSG_INVALID_EMAIL = 'The email must be a valid email address.';
const MSG_INVALID_PASSWORD = 'The password must be at least 6 characters.';
const MSG_EMAIL_REQUIRED = 'The email field is required.';
const MSG_PASSWORD_REQUIRED = 'The password field is required.';

class UserControllerTest extends TestCase
{

  use DatabaseTransactions;
  //use \Codeception\Specify;


  /**
   * I send a GET request to /v1/users/ as an authenticated
   * admin user and the server returns a list of users. 
   *
   * @return void
   */
  public function testCanShowAllUsersWhenAdminUser(){
    $this->actingAs(User::findOrFail(1))->get(USERS_ROUTE)
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
   * I send a GET request to /v1/users/ as an authenticated
   * non-admin user and the server returns a list of users. 
   *
   * @return void
   */
  public function testDoesNotShowAllUsersWhenNotAnAdminUser(){
    $this->actingAs(User::findOrFail(2))->get(USERS_ROUTE)
      ->seeStatusCode(self::HTTP_FORBIDDEN);
  }

  /**
   * I send a GET request to /v1/users/ as an unauthenticated
   * user and the server returns a list of users. 
   *
   * @return void
   */
  public function testDoesNotShowAllUsersWhenNotAuthenticated(){
    $this->get(USERS_ROUTE)
      ->seeStatusCode(self::HTTP_UNAUTHORIZED);
  }


  /**
   * I send a GET request to /v1/users/{id} where {id} is the
   * user id of the currently authenticated user and the server 
   * returns the user with a matching id. 
   *
   * @return void
   */
  public function testCanShowUserWhenAuthenticated(){
    $id = 2;
    
    $this->actingAs(User::findOrFail($id))->get(USERS_ROUTE . '/5/')
      ->seeStatusCode(self::HTTP_OK)
      ->seeJsonStructure([
        'data'  => [
          'email',
          'id'
        ]
      ]);
  }


  /**
   * I send a GET request to /v1/users/{id} where {id} is a
   * valid user id and the server returns the user with a matching
   * id. 
   * (TODO add authentication)
   *
   * @return void
   */
  public function testDoesNotShowUserWhenNotAuthenticated(){
    $this->get(USERS_ROUTE . '/1/')
      ->seeStatusCode(self::HTTP_UNAUTHORIZED);
  }

  /**
   * I send a GET request to /v1/users/{id} where id is
   * an invalid user id and the server returns an error.
   *
   * @expectedException
   *
   * @return void
   */
  public function testDoesNotShowUserWithAnInvalidId(){
    $invalidUserId = 'invalid';
    $this->actingAs(User::findOrFail(1))->get(USERS_ROUTE . "/$invalidUserId/")
      ->seeStatusCode(self::HTTP_NOT_FOUND);
  }
  /**
   * I send a POST request to /v1/users/ with valid
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


    $this->post(USERS_ROUTE, [
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
   * I send a POST request to /v1/users/ with an invalid
   * email and the server sends an appropriate response and
   * does not create a user in the database.
   *
   * @return void
   */
  public function testDoesNotCreateANewUserWithAnInvalidEmail(){
    $this->get('/');
    $email = 'doma';
    $password = 'password99';

    //check that the api responds accordingly
    $this->post(USERS_ROUTE, [
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
   * I send a POST request to /v1/users/ without an
   * email and the server sends an appropriate response and
   * does not create a user in the database.
   *
   * @return void
   */
  public function testDoesNotCreateANewUserWithoutAnEmail(){
    $this->get('/');
    $password = 'password99';

    //check that the api responds accordingly
    $this->post(USERS_ROUTE, [
      'password' => $password
    ])->seeStatusCode(self::HTTP_UNPROCESSABLE_ENTITY)
      ->seeJson([
        MSG_EMAIL_REQUIRED
      ]);
  }



  /**
   * I send a POST request to /v1/users/ without a
   * password and the server sends an appropriate response and
   * does not create a user in the database.
   *
   * @return void
   */
  public function testDoesNotCreateANewUserWithoutAPassword(){
    $this->get('/');
    $email = 'doma@gmail.com';

    //check that the api responds accordingly
    $this->post(USERS_ROUTE, [
      'email' => $email
    ])->seeStatusCode(self::HTTP_UNPROCESSABLE_ENTITY)
      ->seeJson([
        MSG_PASSWORD_REQUIRED
      ]);
  }


  /**
   * I send a POST request to /v1/users/ without an email and
   * password and the server sends an appropriate response and
   * does not create a user in the database.
   *
   * @return void
   */
  public function testDoesNotCreateANewUserWithoutAnEmailAndPassword(){
    $this->get('/');
    $email = 'doma@gmail.com';

    //check that the api responds accordingly
    $this->post(USERS_ROUTE)->seeStatusCode(self::HTTP_UNPROCESSABLE_ENTITY)
      ->seeJson([
        'email' => [MSG_EMAIL_REQUIRED],
        'password' => [MSG_PASSWORD_REQUIRED],
      ]);
  }

  /**
   * I send a POST request to /v1/users/ with an
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
    $this->post(USERS_ROUTE, [
      'email' => $email,
      'password' => $password,
    ])->seeStatusCode(self::HTTP_UNPROCESSABLE_ENTITY)
      ->seeJson([
        MSG_INVALID_PASSWORD 
      ]);
  }

  /**
   * I send a POST request to /v1/users/ with an
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
    $this->post(USERS_ROUTE, [
      'email' => $email,
      'password' => $password,
    ])->seeStatusCode(self::HTTP_UNPROCESSABLE_ENTITY)
      ->seeJson([
        'password' => [MSG_INVALID_PASSWORD],
        'email' => [MSG_INVALID_EMAIL],
      ]);
  }



  /**
   * I send a PUT request to /v1/users/{id}/ and 
   * the server updates the user matching the given id
   * in the database with the received data and returns 
   * the appropriate response.
   * 
   * @return void
   */
  public function testCanUpdateExistingUserWithValidData(){
    $this->get('/');
    $id = 1;
    $user = User::findOrFail($id);
    $email = 'mynewemail@extranew.com';

    $this->assertNotEquals($email, $user->email);
    //check that the api responds accordingly
    $this->actingAs($user)->put(USERS_ROUTE . "/$id/", [
      'email' => $email,
      'password' => 'newPassword'
    ])->seeStatusCode(self::HTTP_OK)
      ->seeJson([
        'data' => "The user with id {$user->id} has been updated."
      ]);

    $this->seeInDatabase('users', ['email' => $email]);
  }


  /**
   * I send a PUT request to /v1/users/{id}/ and 
   * the server updates the user matching the given id
   * in the database with the received data and returns 
   * the appropriate response.
   * 
   * @return void
   */
  public function testDoesNotUpdateExistingUserWithInvalidData(){
    $this->get('/');
    $id = 1;
    $user = User::findOrFail($id);
    $invalidEmail = 'invalid';

    //make sure test email and current email aren't the same
    $this->assertNotEquals($invalidEmail, $user->email);

    //check that the api responds accordingly
    $this->actingAs($user)->put(USERS_ROUTE . "/$id/", [
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
   * I send a PUT request to /v1/users/{id}/ where id is a 
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
    $this->actingAs(User::findOrFail(1))->put(USERS_ROUTE . "/$invalidUserId/", [
      'email' => 'new email',
    ])->seeStatusCode(self::HTTP_NOT_FOUND);
  }

  /**
   * I send a DELETE request to /v1/users/{id} where {id} is a
   * valid user id and the server deletes the user from the database.
   *
   * (TODO add authentication)
   *
   * @return void
   */
  public function testCanDeleteUser(){
    $that = $this;
    $id = 1;
    $that->actingAs(User::findOrFail($id))->delete(USERS_ROUTE . "/$id/")
      ->seeStatusCode(self::HTTP_OK);

    $this->missingFromDatabase('users', ['id' => $id]);
  }

  /**
   * I send a DELETE request to /v1/users/{id} where {id} is an
   * invalid user id and the server responds appropriately.
   *
   * (TODO add authentication)
   *
   * @return void
   */
  public function testDoesNotDeleteInvalidUser(){
    $that = $this;
    $id = 'invalid';
    $that->actingAs(User::findOrFail(1))->delete(USERS_ROUTE . "/$id/")
      ->seeStatusCode(self::HTTP_NOT_FOUND);
  }

}

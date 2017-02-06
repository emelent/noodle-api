<?php

use \Codeception\Util\HttpCode;
use Illuminate\Support\Facades\Hash;

class UserRoutesCest
{
  public function _before(ApiTester $I){
  }

  public function _after(ApiTester $I){
  }

  public function tryToGetAllUsers(ApiTester $I){
    //$I->sendGET('users/');
    //$I->seeResponseCodeIs(HttpCode::OK);
    //$I->seeResponseMatchesJsonType([
      //'email' => 'string',
      //'id'    => 'integer'
    //], '$.data[*]');
  }

  public function tryToStoreANewUser(ApiTester $I){
    $userData = $this->getModule('Lumen')->_request('GET', '/api/v1/users');
    $user = json_decode($userData);
    return $user->id;
    //$email = 'testme@gmail.com';
    //$password = 'password';
    //$I->sendPOST('users/', [
      //'email' => $email,
      //'password' => $password
    //]);
    //$I->seeResponseCodeIs(HttpCode::CREATED);
    //$I->seeResponseMatchesJsonType([
      //'email' => 'string',
      //'id'    => 'integer'
    //], '$.data[*]');
  }
}

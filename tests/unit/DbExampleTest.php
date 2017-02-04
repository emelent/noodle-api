<?php

use App\User;
use Illuminate\Support\Facades\Hash;

class DbExampleTest extends \Codeception\Test\Unit
{

  //use \Codeception\Specify;

  /**
   * @var \UnitTester
   */
  protected $tester;
  private $user;

  protected function _before()
  {
  }

  protected function _after()
  {
  }

  // tests
  public function testMe()
  {
    $id = $this->tester->haveRecord('users', [
      'email' => 'jenko@gmail.com',
      'password'  => 'jenko'
    ]);

    $this->user = User::find($id);
  }
}

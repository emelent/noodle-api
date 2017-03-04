<?php

use Lukasoppermann\Httpstatus\Httpstatuscodes;
use App\User;

abstract class TestCase extends Laravel\Lumen\Testing\TestCase implements Httpstatuscodes
{
		protected $user = null;
  	protected $admin = null;

  	
  /**
   * Seems to fix request issue when running this with codeception
   * instead of phpunit. This just sends a get request, without
   * this, all non-GET requests bug out.
   * 
   * @return void
   */
  protected function requestHack(){
    $this->get('/');
  }

  public function getUserByRole($role){
    return User::all()->filter(function($user) use($role){
      return $user->roles()->where('role', $role)->get();
    })->first();
  }

  public function getAdminUser(){
    if(!$this->admin){
      $this->admin = $this->getUserByRole('admin');
    }
    return $this->admin;
  }

  public function getUser(){
    if(!$this->user){
      $this->user = $this->getUserByRole('user');
    }
    return $this->user;
  }
  
    /**
     * Creates the application.
     *
     * @return \Laravel\Lumen\Application
     */
    public function createApplication()
    {
        return require __DIR__.'/../bootstrap/app.php';
    }
}

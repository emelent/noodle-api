<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


use App\User;
use App\Role;

class UserController extends ModelController{

	protected $storeRules = [
		'email' => 'required|email|unique:users', 
		'password' => 'required|min:6'
	];

	protected $updateRules = [
		'email' => 'email|unique:users', 
		'password' => 'min:6'
	];

  public function __construct(){
  	parent::__construct(User::class);
    $this->middleware('auth:api', ['except'	=> ['store']]);
    $this->middleware('role:admin', ['only' => ['showAll']]);
  }

	public function store(Request $request){
		$this->validateStoreRequest($request);
		$user = User::create([
			'email' => $request->get('email'),
			'password'=> Hash::make($request->get('password'))
		]);

		//add user role to new user
		$user->roles()->attach(Role::where('role', 'user')->first()->id);
		return $this->success("The user with email {$user->email} has been created.", self::HTTP_CREATED);
	}

	public function update(Request $request, $id){
		$user = User::find($id);

		if(!$user){
			return $this->error("The user with {$id} doesn't exist", self::HTTP_NOT_FOUND);
		}

		//can only update current user unless current user is admin 
		if($id != $request->user()->id && !$this->isAdmin($request->user())){
			return $this->error("Not permitted.", self::HTTP_FORBIDDEN);
		}

		$this->validateUpdateRequest($request);
		$user->email 		= $request->get('email');
		$user->password 	= Hash::make($request->get('password'));
		$user->save();
		
		return $this->success("The user with id {$user->id} has been updated.", self::HTTP_OK);
	}

	public function destroy(Request $request, $id)
	{
		//can only update current user unless current user is admin 
		if($id != $request->user()->id && !$this->isAdmin($request->user())){
			return $this->error("Not permitted.", self::HTTP_FORBIDDEN);
		}

		return $this->destroyAction($request, $id, false);
	}
}

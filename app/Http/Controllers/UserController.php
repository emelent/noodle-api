<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\JWTAuth;


class UserController extends Controller{

  protected $jwt;

  public function __construct(JWTAuth $jwt){
    $this->jwt = $jwt;

    $this->middleware('auth:api', ['except'	=> ['store']]);
    $this->middleware('role:admin', ['only' => ['showAll']]);
  }

	public function show($id){
		$user = User::find($id);
		if(!$user){
			return $this->error("The user with {$id} doesn't exist.", self::HTTP_NOT_FOUND);
		}
		return $this->success($user, self::HTTP_OK);
	}

	public function showAll(Request $request){
		$users = User::all();
		return $this->success($users, self::HTTP_OK);
	}

	public function store(Request $request){
		$this->validateRequest($request);
		$user = User::create([
			'email' => $request->get('email'),
			'password'=> Hash::make($request->get('password'))
		]);
		return $this->success("The user with email {$user->email} has been created.", self::HTTP_CREATED);
	}

	public function update(Request $request, $id){
		$user = User::find($id);
		if(!$user){
			return $this->error("The user with {$id} doesn't exist", self::HTTP_NOT_FOUND);
		}

		if($user != $request->user()){
			return $this->error("The user with {$id} doesn't exist", self::HTTP_UNAUTHORIZED);
		}
		$this->validateRequest($request);
		$user->email 		= $request->get('email');
		$user->password 	= Hash::make($request->get('password'));
		$user->save();
		return $this->success("The user with id {$user->id} has been updated.", self::HTTP_OK);
	}

	public function destroy($id){
		$user = User::find($id);
		if(!$user){
			return $this->error("The user with {$id} doesn't exist.", self::HTTP_NOT_FOUND);
		}
		$user->delete();
		return $this->success("The user has been deleted.", self::HTTP_OK);
	}

	public function validateRequest(Request $request){
		$rules = [
			'email' => 'required|email|unique:users', 
			'password' => 'required|min:6'
		];
		$this->validate($request, $rules);
	}
}

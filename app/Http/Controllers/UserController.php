<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller{

	public function index(){
		$users = User::all();
		return $this->success($users, self::HTTP_OK);
	}

	public function store(Request $request){
		$this->validateRequest($request);
		$user = User::create([
					'email' => $request->get('email'),
					'password'=> Hash::make($request->get('password'))
				]);
		return $this->success("The user with with email {$user->email} has been created", self::HTTP_CREATED);
	}

	public function show($id){
		$user = User::find($id);
		if(!$user){
			return $this->error("The user with {$id} doesn't exist", self::HTTP_NOT_FOUND);
		}
		return $this->success($user, self::HTTP_OK);
	}

	public function update(Request $request, $id){
		$user = User::find($id);
		if(!$user){
			return $this->error("The user with {$id} doesn't exist", self::HTTP_NOT_FOUND);
		}
		$this->validateRequest($request);
		$user->email 		= $request->get('email');
		$user->password 	= Hash::make($request->get('password'));
		$user->save();
		return $this->success("The user with with id {$user->id} has been updated", self::HTTP_OK);
	}

	public function destroy($id){
		$user = User::find($id);
		if(!$user){
			return $this->error("The user with {$id} doesn't exist", self::HTTP_NOT_FOUND);
		}
		$user->delete();
		return $this->success("The user with with id {$id} has been deleted", self::HTTP_OK);
	}

	public function validateRequest(Request $request){
		$rules = [
			'email' => 'required|email|unique:users', 
			'password' => 'required|min:6'
		];
		$this->validate($request, $rules);
	}
}
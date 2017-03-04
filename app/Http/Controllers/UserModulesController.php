<?php
namespace App\Http\Controllers;

use App\User;
use App\Module;
use Illuminate\Http\Request;

/**
* 
*/
class UserModulesController extends Controller
{

	function __construct()
	{
		$this->middleware('auth:api');
		// $this->middleware('role:user');
	}

	public function showModules(Request $request,  $id)
	{
		$user = User::find($id);
		if (!$user) {
			return $this->error("The user with $id doesn't exist.", self::HTTP_NOT_FOUND);
		}

		return $this->success($user->modules()->get(), self::HTTP_OK);
	}

	public function addModules(Request $request, $id)
	{
		$user = User::find($id);
		if (!$user) {
			return $this->error("The user with $id doesn't exist.", self::HTTP_NOT_FOUND);
		}

		$rules = [
			'modules' => 'required|json'
		];

		//add modules to related user
		$this->validate($request, $rules);
		$moduleIds = json_decode($request->input('modules'), true);
		$user->modules()->attach($moduleIds);

		$numModules = count($moduleIds);
		return $this->success("Added $numModules module(s) to user.", self::HTTP_OK);		
	}

	
	public function removeModules(Request $request, $id)
	{
		$user = User::find($id);
		if (!$user) {
			return $this->error("The user with $id doesn't exist.", self::HTTP_NOT_FOUND);
		}

		$rules = [
			'modules' => 'required|json'
		];

		//remove modules from related user
		$this->validate($request, $rules);
		$moduleIds = json_decode($request->input('modules'), true);
		$user->modules()->detach($moduleIds);
		$numModules = count($moduleIds);
		return $this->success("Removed $numModules module(s) from user.", self::HTTP_OK);		
	}

}
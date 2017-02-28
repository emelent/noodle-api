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
		$this->role('role:user');
	}

	public function showModules(Request $request,  $id)
	{
		$user = User::find($id);
		if (!$user) {
			return $this->error("The user with $id doesn't exist.", self::HTTP_NOT_FOUND);
		}

		return $this->success($user->modules()->get());
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
		$moduleIds = json_encode($request->input('modules'), true);
		$user->modules()->attach($moduleIds);

		return $this->success('Modules successfully added to user.', self::HTTP_OK);		
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
		$moduleIds = json_encode($request->input('modules'), true);
		$user->modules()->detach($moduleIds);

		return $this->success('Modules successfully removed from user.', self::HTTP_OK);		
	}

}
<?php
namespace App\Http\Controllers;

use App\User;
use App\Module;
use Illuminate\Http\Request;

/**
* 
*/
class CurrentUserController extends Controller
{

	function __construct()
	{
		$this->middleware('auth:api');
		$this->middleware('role:user');
	}

  public function showUser(Request $request){
    return $this->success($request->user(), self::HTTP_OK);
  }
	public function showModules(Request $request)
	{
		$user = $request->user();

		return $this->success($user->modules()->get(), self::HTTP_OK);
	}

	public function addModules(Request $request)
	{
		$user = $request->user();
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

	
	public function removeModules(Request $request)
	{
		$user = $request->user();
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


	public function updateUser(Request $request){
		$user = $request->user();
    $rules = [
      'email' => 'email|unique:users', 
      'password' => 'min:6'
    ];

		$this->validate($request, $rules);
		$user->email = $request->get('email');
		$user->password = Hash::make($request->get('password'));
		$user->save();
		
		return $this->success("The user has been updated.", self::HTTP_OK);
	}

	public function showTimetables(Request $request)
	{
		$user = $request->user();
		return $this->success($user->timetables()->get(), self::HTTP_OK);
	}

	public function addTimetable(Request $request)
	{
		$user = $request->user();
    $rules = [
      'timetable' => 'integer'
    ];

		//add timetables to related user
		$this->validate($request, $rules);
		$user->timetables()->attach($request->input('timetable'));
		return $this->success("Timetable added.", self::HTTP_OK);		
	}

	
	public function removeTimetables(Request $request, $id)
	{
		$user = $request->user();
		$rules = [
			'timetables' => 'required|json'
		];

		//remove timetables from related user
		$this->validate($request, $rules);
		$timetableIds = json_decode($request->input('timetables'), true);
		$user->timetables()->detach($timetableIds);

		$numTimetables = count($timetableIds);
		return $this->success("Removed $numTimetables timetable(s).", self::HTTP_OK);		
	}
}

<?php
namespace App\Http\Controllers;

use App\User;
use App\Timetable;
use Illuminate\Http\Request;

/**
* 
*/
class UserTimetablesController extends Controller
{

	function __construct()
	{
		$this->middleware('auth:api');
		$this->role('role:user');
	}

	public function showTimetables(Request $request,  $id)
	{
		$user = User::find($id);
		if (!$user) {
			return $this->error("The user with $id doesn't exist.", self::HTTP_NOT_FOUND);
		}

		return $this->success($user->timetables()->get());
	}

	public function addTimetables(Request $request, $id)
	{
		$user = User::find($id);
		if (!$user) {
			return $this->error("The user with $id doesn't exist.", self::HTTP_NOT_FOUND);
		}

		$rules = [
			'timetables' => 'required|json'
		];

		//add timetables to related user
		$this->validate($request, $rules);
		$timetableIds = json_encode($request->input('timetables'), true);
		$user->timetables()->attach($timetableIds);

		return $this->success('Timetables successfully added to user.', self::HTTP_OK);		
	}

	
	public function removeTimetables(Request $request, $id)
	{
		$user = User::find($id);
		if (!$user) {
			return $this->error("The user with $id doesn't exist.", self::HTTP_NOT_FOUND);
		}

		$rules = [
			'timetables' => 'required|json'
		];

		//remove timetables from related user
		$this->validate($request, $rules);
		$timetableIds = json_encode($request->input('timetables'), true);
		$user->timetables()->detach($timetableIds);

		return $this->success('Timetables successfully removed from user.', self::HTTP_OK);		
	}

}
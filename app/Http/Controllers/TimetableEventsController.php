<?php
namespace App\Http\Controllers;

use App\Timetable;
use App\Event;
use Illuminate\Http\Request;

/**
* 
*/
class TimetableEventsController extends Controller
{

	function __construct()
	{
		$this->middleware('auth:api');
		$this->middleware('role:user');
	}

	public function showEvents(Request $request,  $id)
	{
		$table = Timetable::find($id);
		if (!$table) {
			return $this->error("The timetable with $id doesn't exist.", self::HTTP_NOT_FOUND);
		}

		return $this->success($table->events()->get(), self::HTTP_OK);
	}

	public function addEvents(Request $request, $id)
	{
		$table = Timetable::find($id);
		if (!$table) {
			return $this->error("The timetable with $id doesn't exist.", self::HTTP_NOT_FOUND);
		}

		$rules = [
			'events' => 'required|json'
		];

		//add events to related tables
		$this->validate($request, $rules);
		$eventIds = json_decode($request->input('events'), true);
		$numEvents = count($eventIds);
		$table->events()->attach($eventIds);

		$table->updateEventsHash();
		return $this->success("Added $numEvents event(s) to timetable.", self::HTTP_OK);		
	}

	
	public function removeEvents(Request $request, $id)
	{
		$table = Timetable::find($id);
		if (!$table) {
			return $this->error("The timetable with $id doesn't exist.", self::HTTP_NOT_FOUND);
		}

		$rules = [
			'events' => 'required|json'
		];

		//remove events from related table
		$this->validate($request, $rules);
		$eventIds = json_decode($request->input('events'), true);
		$table->events()->detach($eventIds);

		$table->updateEventsHash();
		$numEvents = count($eventIds);
		return $this->success("Removed $numEvents event(s) from timetable.", self::HTTP_OK);		
	}

}
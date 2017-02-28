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
		$this->role('role:user');
	}

	public function showEvents(Request $request,  $id)
	{
		$table = Timetable::find($id);
		if (!$table) {
			return $this->error("The timetable with $id doesn't exist.", self::HTTP_NOT_FOUND);
		}

		return $this->success($table->events()->get());
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

		//add events to related table
		$this->validate($request, $rules);
		$eventIds = json_encode($request->input('events'), true);
		$table->events()->attach($eventIds);

		$this->updateTableHash($table);
		return $this->success('Events successfully added to timetable.', self::HTTP_OK);		
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
		$eventIds = json_encode($request->input('events'), true);
		$table->events()->detach($eventIds);

		//update table hash
		$this->updateTableHash($table);

		return $this->success('Events successfully removed from timetable.', self::HTTP_OK);		
	}

	protected function updateTableHash($table)
	{
		$eventIds = $table->events()->sortBy('id')->map(function($event){
			return $event->id;
		});
		$table->hash = hash('sha256', implode('#', $eventIds));
	}

}
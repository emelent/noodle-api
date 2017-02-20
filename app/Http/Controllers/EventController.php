<?php

namespace App\Http\Controllers;

use App\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class EventController extends Controller{

	public function index(){
		$events = Event::all();
		return $this->success($events, self::HTTP_OK);
	}

	public function store(Request $request){
		$this->validateRequest($request);
    //TODO get current user ID
    //
    $user_id = 1;
		$event = Event::create([
      'name' => $request->get('name'),
      'date' => $request->get('date'),
      'day' => $request->get('day'),
      'start' => $request->get('start'),
      'end' => $request->get('end'),
      'language' => $request->get('language'),
      'group' => $request->get('group'),
      'creator_id' => $user_id,
      'module_id' => $request->get('module_id'),
    ]);
		return $this->success("The event has been created.", self::HTTP_CREATED);
	}

	public function show($id){
		$event = Event::find($id);
		if(!$event){
			return $this->error("The event with {$id} doesn't exist.", self::HTTP_NOT_FOUND);
		}
		return $this->success($event, self::HTTP_OK);
	}

	public function update(Request $request, $id){
		$event = Event::find($id);
		if(!$event){
			return $this->error("The event with {$id} doesn't exist", self::HTTP_NOT_FOUND);
		}
		$this->validateRequest($request);
		$event->name = $request->get('name');
		$event->date = $request->get('date');
		$event->start = $request->get('start');
		$event->end = $request->get('end');
		$event->date = $request->get('date');
		$event->language = $request->get('language');
		$event->group = $request->get('group');
		$event->module_id = $request->get('module_id');
		$event->save();
		return $this->success("The event with id {$event->id} has been updated.", self::HTTP_OK);
	}

	public function destroy($id){
		$event = Event::find($id);
		if(!$event){
			return $this->error("The event with {$id} doesn't exist.", self::HTTP_NOT_FOUND);
		}
		$event->delete();
		return $this->success("The event has been deleted.", self::HTTP_OK);
	}

  public function search(){
    return $this->index();
  }
	public function validateRequest(Request $request){
		$rules = [
			'email' => 'required|email|unique:events', 
			'password' => 'required|min:6'
		];
		$this->validate($request, $rules);
	}
}

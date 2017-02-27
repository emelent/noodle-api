<?php

namespace App\Http\Controllers;

use App\Event;
use Illuminate\Http\Request;

class EventController extends ModelController{

  public function __construct(){
    parent::__construct(Event::class);
    $this->middleware('auth:api', ['except' => [
    	'show', 'showAll'
    ]]);
  }

	public function store(Request $request){
		$this->validateRequest($request);

		$event = Event::create([
      'name' => $request->get('name'),
      'date' => $request->get('date'),
      'day' => $request->get('day'),
      'start' => $request->get('start'),
      'end' => $request->get('end'),
      'language' => $request->get('language'),
      'group' => $request->get('group'),
      'creator_id' => $request->user()->id,
      'module_id' => $request->get('module_id'),
    ]);

		return $this->success("The event has been created.", self::HTTP_CREATED);
	}

	public function update(Request $request, $id){
		$event = Event::find($id);
		if(!$event){
			return $this->error("The event with {$id} doesn't exist", self::HTTP_NOT_FOUND);
		}

		if(!$event->creator_id != $request->user()->id){
			return $this->error("Unauthorized.", self::HTTP_UNAUTHORIZED);
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

	public function validateRequest(Request $request){
		//$rules = [
			//'email' => 'required|email|unique:events', 
			//'password' => 'required|min:6'
		//];
		//$this->validate($request, $rules);
	}
}

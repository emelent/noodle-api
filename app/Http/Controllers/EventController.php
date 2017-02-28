<?php

namespace App\Http\Controllers;

use App\Event;
use Illuminate\Http\Request;

class EventController extends ModelController{

	protected $storeRules = [
		'name'	=> 'required|string',
		'day'		=> 'required|integer|between:1,7',
		'start'	=> 'required|regex:/^(\d{2})(:)(\d{2})$/',
		'end'	=> 'required|regex:/^(\d{2})(:)(\d{2})$/',
		'date'	=> 'required|date',
		'language'	=> 'required|integer',
		'creator_id'	=> 'required|integer',
		'module_id'	=> 'required|integer',
		'group'	=> 'integer'
	];

	protected $updateRules = [
		'name'	=> 'string',
		'day'		=> 'integer|between:1,7',
		'start'	=> 'regex:/^(\d{2})(:)(\d{2})$/',
		'end'	=> 'regex:/^(\d{2})(:)(\d{2})$/',
		'date'	=> 'date',
		'language'	=> 'integer',
		'creator_id'	=> 'integer',
		'module_id'	=> 'integer',
		'group'	=> 'integer',
	];

  public function __construct(){
    parent::__construct(Event::class);
    $this->middleware('auth:api', ['except' => [
    	'show', 'showAll'
    ]]);
  }


	public function update(Request $request, $id){
		$event = Event::find($id);
		if(!$event){
			return $this->error("The event with $id doesn't exist", self::HTTP_NOT_FOUND);
		}
		if($event->creator_id != $request->user()->id){
			return $this->error("Not permitted.", self::HTTP_UNAUTHORIZED);
		}
		parent::update($request, $id);
	}


	public function destroy(Request $request, $id){
		$event = Event::find($id);
		if(!$event){
			return $this->error("The event with $id doesn't exist", self::HTTP_NOT_FOUND);
		}
		if($event->creator_id != $request->user()->id){
			return $this->error("Not permitted.", self::HTTP_UNAUTHORIZED);
		}
		parent::destroy($request, $id);
	}
}

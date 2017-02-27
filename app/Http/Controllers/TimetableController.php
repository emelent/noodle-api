<?php

namespace App\Http\Controllers;

use App\Timetable;
use Illuminate\Http\Request;

class TimetableController extends ModelController{

  public function __construct(){
    parent::__construct(Timetable::class);
    $this->middleware('auth:api', ['except'	=> ['show','showAll']]);
    $this->middleware('role:user', ['except'	=> ['show','showAll']]);
  }

	public function store(Request $request){
		//$this->validateRequest($request);

    ////TODO get current user ID
    //$user_id = 1; // oh, you dirty, dirty line of code

		//$timetable = Timetable::create([
      //'name' => $request->get('name'),
      //'description' => $request->get('description'),
      //'code' => $request->get('code'),
      //'type' => $request->get('type'),
      //'period' => $request->get('period'),
    //]);
		//return $this->success("The timetable has been created.", self::HTTP_CREATED);
    return $this->error('Not implemented', 500);
	}

	public function update(Request $request, $id){
		$timetable = Timetable::find($id);
		if(!$timetable){
			return $this->error("The timetable with {$id} doesn't exist", self::HTTP_NOT_FOUND);
		}
		//$this->validateRequest($request);
		//$timetable->save();
		//return $this->success("The timetable with id {$timetable->id} has been updated.", self::HTTP_OK);
    return $this->error('Not implemented', 500);
	}

	public function validateRequest(Request $request){
		//$rules = [
			//'email' => 'required|email|unique:timetables', 
			//'password' => 'required|min:6'
		//];
		//$this->validate($request, $rules);
	}
}

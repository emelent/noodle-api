<?php

namespace App\Http\Controllers;

use App\Timetable;
use Illuminate\Http\Request;

class TimetableController extends ModelController{

	protected $storeRules = [
		'creator_id'	=> 'required|integer'
	];

  public function __construct(){
    parent::__construct(Timetable::class);
    $this->middleware('auth:api', ['except'	=> ['show','showAll']]);
    $this->middleware('role:user', ['except'	=> ['show','showAll']]);
  }

	public function store(Request $request){
		$request->merge(['creator_id' => $request->user()->id]);
		return $this->storeAction($request);
	}

	public function destroy(Request $request, $id){
		$result = $this->validateOwnership($request, $id);
		if($result != true){
			return $result;
		}

		return parent::destroy($request, $id);
	}
}

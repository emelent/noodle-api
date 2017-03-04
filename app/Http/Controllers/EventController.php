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
		'language'	=> 'required|integer',
		'creator_id'	=> 'required|integer',
		'module_id'	=> 'required|integer',
		'group'	=> 'integer',		
		'date'	=> 'date'
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
    $this->middleware('auth:api');
    $this->middleware('role:user');
  }


	public function update(Request $request, $id){
		//check ownership, unless user is admin
		return parent::update($request, $id, !$this->isAdmin($request->user()));
	}

	public function store(Request $request)
	{
		$user = $request->user();
		$request->merge(['creator_id' => $user->id]);
		return parent::store($request);
	}

	public function destroy(Request $request, $id){
		return parent::destroy($request, $id, true);
	}
}

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
		return parent::update($request, $id, true);
	}

	public function store(Request $request)
	{
		$user = $request->user();
		$request->input('creator_id', $user->id);
		if($request->input('creator_id') != $user->id){
			return $this->error('Invalid creator_id.', self::HTTP_UNPROCESSABLE_ENTITY);
		}
		return parent::store($request);
	}

	public function destroy(Request $request, $id){
		return parent::destroy($request, $id, true);
	}
}

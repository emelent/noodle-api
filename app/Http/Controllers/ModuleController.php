<?php

namespace App\Http\Controllers;

use App\Module;
use Illuminate\Http\Request;

class ModuleController extends ModelController{

	protected $storeRules = [
		'name'	=>	'required',
		'description' => 'required',
		'code'	=> 'required|unique:modules|regex:/^([a-z|A-Z]{3})(\d{3})$/',
		'period'	=> 'required|min:1|max:2|regex:/^(([sqSQ]{1})([1-4])){0,2}([yY]{0,1})$/',
		'postgrad'	=> 'required|boolean'
	];

	protected $updateRules = [
			'code'	=> 'unique:modules|regex:/^([a-z|A-Z]{3})(\d{3})$/',
			'period'	=> 'min:1|max:2|regex:/^(([sqSQ]{1})([1-4])){0,2}([yY]{0,1})$/',
			'postgrad'	=> 'boolean'
	];

  public function __construct(){
    parent::__construct(Module::class);
    $this->middleware('auth:api', ['except' => [
    	'show', 'showAll'
    ]]);
    $this->middleware('role:admin', ['except' => [
    	'show', 'showAll'
    ]]);
  }
}

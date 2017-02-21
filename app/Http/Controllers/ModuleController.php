<?php

namespace App\Http\Controllers;

use App\Module;
use Illuminate\Http\Request;

class ModuleController extends ModelController{

  public function __construct(){
    parent::__construct(Module::class);
  }

	public function store(Request $request){
		//$this->validateRequest($request);

    ////TODO get current user ID
    //$user_id = 1; // oh, you dirty, dirty line of code

		//$module = Module::create([
      //'name' => $request->get('name'),
      //'description' => $request->get('description'),
      //'code' => $request->get('code'),
      //'type' => $request->get('type'),
      //'period' => $request->get('period'),
    //]);
		//return $this->success("The module has been created.", self::HTTP_CREATED);
    return $this->error('Not implemented', 500);
	}

	public function update(Request $request, $id){
		$module = Module::find($id);
		if(!$module){
			return $this->error("The module with {$id} doesn't exist", self::HTTP_NOT_FOUND);
		}
		//$this->validateRequest($request);
		//$module->save();
		//return $this->success("The module with id {$module->id} has been updated.", self::HTTP_OK);
    return $this->error('Not implemented', 500);
	}

	public function validateRequest(Request $request){
		//$rules = [
			//'email' => 'required|email|unique:modules', 
			//'password' => 'required|min:6'
		//];
		//$this->validate($request, $rules);
	}
}

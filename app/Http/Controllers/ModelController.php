<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;


/**
 * Base Model Controller class which implements generic CRUD
 * endpoint behaviour.
 */
class ModelController extends Controller{

  protected $modelClass;

  public function __construct($modelClass){
    $this->modelClass = $modelClass;
  }

	public function showAll(){
    //TODO implement search
    $cName = $this->modelClass;
		$models = $cName::all();
		return $this->success($models, self::HTTP_OK);
	}

	public function show($id){
    $cName = $this->modelClass;
    $lowerName = strtolower($cName);
		$model = $cName::find($id);
		if(!$model){
			return $this->error("The ${lowerName} with {$id} doesn't exist.", self::HTTP_NOT_FOUND);
		}
		return $this->success($model, self::HTTP_OK);
	}

	public function destroy($id){
    $cName = $this->modelClass;
    $lowerName = strtolower($cName);
		$model = $cName::find($id);
		if(!$model){
			return $this->error("The {$lowerName} with {$id} doesn't exist.", self::HTTP_NOT_FOUND);
		}
		$model->delete();
		return $this->success("The {$lowerName} has been deleted.", self::HTTP_OK);
	}
}

<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;


/**
 * Base Model Controller class which implements generic CRUD
 * endpoint behaviour.
 */
class ModelController extends Controller{

  protected $modelClass;
  protected $updateRules;
  protected $storeRules;
  
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
			return $this->error("The $lowerName with $id doesn't exist.", self::HTTP_NOT_FOUND);
		}
		return $this->success($model, self::HTTP_OK);
	}

	public function destroy(Request $request, $id, $validateOwnership){
    $cName = $this->modelClass;
    $lowerName = strtolower($cName);
		$model = $cName::find($id);
		if(!$model){
			return $this->error("The $lowerName with $id doesn't exist.", self::HTTP_NOT_FOUND);
		}

		if($validateOwnership){
			if($model->$key != $value)
				return $this->error('Not permitted.', self::HTTP_FORBIDDEN);
		}

		$model->delete();
		return $this->success("The $lowerName has been deleted.", self::HTTP_OK);
	}

	public function update(Request $request, $id, $validateOwnership=false){
		$cName = $this->modelClass;
    $lowerName = strtolower($cName);
		$model = $cName::find($id);
		if(!$model){
			return $this->error("The $lowerName with $id doesn't exist", self::HTTP_NOT_FOUND);
		}

		if($validateOwnership){
			if($model->$key != $value)
				return $this->error('Not permitted.', self::HTTP_FORBIDDEN);
		}
		$this->validateUpdateRequest($request);

		//update values
		$fields = $request->all();
		$fillables = $model->getFillable();
		$validFields = array_intersect(array_keys($fields), $fillables);
		foreach($validFields as $field){
			$model->$field = $fields[$field];
		}

		$model->save();
		return $this->success("The $lowerName has been updated.", self::HTTP_OK);
	}


	public function store(Request $request){
		$cName = $this->modelClass;
    $lowerName = strtolower($cName);
		$this->validateStoreRequest($request);

		$fields = (new $cName())->getFillable();
		$data = [];
		foreach($fields as $field){
			$data[$field] = $request->get($field);
		}
		$model = $cName::create($data);
		return $this->success("The $lowerName has been created.", self::HTTP_CREATED);
	}

	public function validateUpdateRequest(Request $request){
		$this->validate($request, $this->updateRules);
	}

	public function validateStoreRequest(Request $request){
		$this->validate($request, $this->storeRules);
	}

	public function validateKey($id, $key, $value, $msg, $code){
		$cName = $this->modelClass;
    $lowerName = strtolower($cName);
		$model = $cName::find($id);

		if(!$model){
			return $this->error("The $lowerName with $id doesn't exist", self::HTTP_NOT_FOUND);
		}

		if($model->$key == $value){
			return true;
		}

		return $this->error($msg, $code);
	}

	public function validateOwnership($request, $id, $key='creator_id'){
		return $this->validateKey(
			$id, 
			$key, 
			$request->user()->id,
			'Not permitted.',
			self::HTTP_FORBIDDEN
		);
	}

	public function isAdmin($user)
	{
		if(!$user)
			return null;
		
		return $user->roles()->where('role', 'admin')->get() != null;
	}
}

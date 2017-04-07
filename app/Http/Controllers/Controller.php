<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Lukasoppermann\Httpstatus\Httpstatuscodes;

class Controller extends BaseController implements Httpstatuscodes
{
  public function success($data, $code){
    return response()->json($data, $code);
  } 

  public function error($message, $code){
    return response()->json(['error' => $message], $code);
  }

  public function isCreator($user, $model){
    return $user->id == $model->creator_id;
  }
}

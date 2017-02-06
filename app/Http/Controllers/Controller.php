<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Lukasoppermann\Httpstatus\Httpstatuscodes;

class Controller extends BaseController implements Httpstatuscodes
{
  public function success($data, $code){
    return response()->json(['data' => $data], $code);
  } 

  public function error($message, $code){
    return response()->json(['message' => $message], $code);
  }
}

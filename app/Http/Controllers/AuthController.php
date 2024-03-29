<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tymon\JWTAuth\JWTAuth;
use Tymon\JWTAuth\Manager;

class AuthController extends Controller
{
  /**
   * @var \Tymon\JWTAuth\JWTAuth
   */
  protected $jwt;

  protected $manager;

  public function __construct(JWTAuth $jwt, Manager $manager)
  {
    $this->jwt = $jwt;
    $this->manager = $manager;
		$this->middleware('auth:api', ['only' => ['refreshToken']]);
  }

  public function issueToken(Request $request)
  {
    $this->validate($request, [
      'email'    => 'required|email|max:255',
      'password' => 'required',
    ]);

    try {
      if (! $token = $this->jwt->attempt($request->only('email', 'password'))) {
        return response()->json(['error' => 'Invalid email or password.'], self::HTTP_UNAUTHORIZED);
      }
    } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
      return response()->json(['error' => 'Token expired.'], self::HTTP_UNAUTHORIZED);
      // return response()->json(['token_expired'], self::HTTP_UNAUTHORIZED);
    } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
      return response()->json(['error' => 'Invalid token.'], self::HTTP_UNAUTHORIZED);
      // return response()->json(['token_invalid'], self::HTTP_UNAUTHORIZED);
    } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
      return response()->json(['error' => 'Missing token.'], self::HTTP_BAD_REQUEST);
      // return response()->json(['token_absent' => $e->getMessage()],  self::HTTP_BAD_REQUEST);
    }
    return response()->json(compact('token'), self::HTTP_OK);
  }

  public function refreshToken(){
    try{
      $token = $this->manager->refresh($this->jwt->getToken())->get();
      return response()->json(compact('token'), self::HTTP_OK);
    }catch(JWTException $e){
      $error = $e->getMessage();
      return response()->json(compact('error'), self::HTTP_INTERNAL_SERVER_ERROR);
    }
  }
}

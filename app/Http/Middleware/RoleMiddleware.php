<?php

namespace App\Http\Middleware;

use Closure;

class RoleMiddleware
{
  /**
   * Handle an incoming request.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \Closure  $next
   * @return mixed
   */
  public function handle($request, Closure $next, $name){
    if(!$request->user()->roles()->where('role', $name)->first()){
      return response()->json(['message' => 'Unauthorized.'], 401);
    }

    return $next($request);
  }
}

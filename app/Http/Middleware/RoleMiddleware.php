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
  public function handle($request, Closure $next, $role){
    if(!$request->user()->roles()->where('role', $role)->first()){
      return response()->json(['message' => 'Not permitted.'], 403);
    }

    return $next($request);
  }
}

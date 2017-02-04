<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$app->get('/', function () use ($app) {
    return $app->version();
});

$app->group([
  'prefix'  =>  '/api/v1/',
], function() use ($app){

  $app->get('/', function() use($app){
    return response()->json(['data' => "Looks like you're well ReSted"]);
  });


  /*user routes*/
  $app->group([
    'prefix'  =>  'users/',
  ], function() use ($app){
    $app->get('/', 'UserController@index');
    $app->post('/', 'UserController@store');
    $app->get('/{user_id}', 'UserController@show');
    $app->put('/{user_id}', 'UserController@update');
    $app->delete('/{user_id}', 'UserController@destroy');
  });
});

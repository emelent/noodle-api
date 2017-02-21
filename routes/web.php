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
    $app->get('/', 'UserController@showAll');
    $app->post('/', 'UserController@store');
    $app->get('/{user_id}', 'UserController@show');
    $app->put('/{user_id}', 'UserController@update');
    $app->delete('/{user_id}', 'UserController@destroy');
  });

  /*event routes*/
  $app->group([
    'prefix'  =>  'events/',
  ], function() use ($app){
    $app->get('/', 'EventController@showAll');
    $app->post('/', 'EventController@store');
    $app->get('/{event_id}', 'EventController@show');
    $app->put('/{event_id}', 'EventController@update');
    $app->delete('/{event_id}', 'EventController@destroy');
  });

  /*module routes*/
  $app->group([
    'prefix'  =>  'modules/',
  ], function() use ($app){
    $app->get('/', 'ModuleController@showAll');
    $app->post('/', 'ModuleController@store');
    $app->get('/{module_id}', 'ModuleController@show');
    $app->put('/{module_id}', 'ModuleController@update');
    $app->delete('/{module_id}', 'ModuleController@destroy');
  });
});

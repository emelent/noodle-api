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
    return response()->json(['data' => "Looks like you're well ReSted"]);
});


$app->group([
  'prefix'  =>  '/v1/',
], function() use ($app){
  $app->post('/auth/login', 'AuthController@issueToken');
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

    /*user module routes*/
    $app->group([
      'prefix'  =>  '/{user_id}/modules/',
    ], function() use($app){
      $app->get('/', 'UserModuleController@showAll');
      $app->delete('/', 'UserModuleController@remove');
      $app->post('/', 'UserModuleController@add');
    });

    /*user timetable routes*/
    $app->group([
      'prefix'  =>  '/{user_id}/timetables/'
    ], function() use($app){
      $app->get('/', 'UserTimetableController@showAll');
      $app->delete('/', 'UserTimetableController@remove');
      $app->post('/', 'UserTimetableController@add');
    });
  });

  /*event routes*/
  $app->group([
    'prefix'  =>  'events/',
  ], function() use ($app){
    $app->post('/', 'EventController@store');
    $app->get('/', 'EventController@showAll');
    $app->get('/{event_id}', 'EventController@show');
    $app->put('/{event_id}', 'EventController@update');
    $app->delete('/{event_id}', 'EventController@destroy');
  });

  /*module routes*/
  $app->group([
    'prefix'  =>  'modules/',
  ], function() use ($app){
    $app->post('/', 'ModuleController@store');
    $app->get('/', 'ModuleController@showAll');
    $app->get('/{module_id}', 'ModuleController@show');
    $app->put('/{module_id}', 'ModuleController@update');
    $app->delete('/{module_id}', 'ModuleController@destroy');
  });

  /*timetable routes*/
  $app->group([
    'prefix'  =>  'timetables/',
    'middleware'  => 'auth:api',
  ], function() use ($app){
    $app->post('/', 'TimetableController@store');
    $app->get('/', 'TimetableController@showAll');
    $app->get('/{timetable_id}', 'TimetableController@show');
    $app->delete('/{module_id}', 'TimetableController@destroy');

    /*timetable event routes*/
    $app->group([
      'prefix'  =>  '/{timetable_id}/events/',
      'middleware'  => 'auth:api',
    ], function() use($app){
      $app->get('/', 'TimetableEventController@showAll');
      $app->delete('/', 'TimetableEventController@remove');
      $app->post('/', 'TimetableEventController@add');
    });
  });
});

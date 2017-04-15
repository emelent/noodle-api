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
  $app->get('/auth/refresh', 'AuthController@refreshToken');
  $app->get('/', function() use($app){
    return response()->json("Looks like you're well ReSted");
  });

  $app->group([
    'prefix' => '/user/'
  ], function() use ($app){
    $app->get('/', 'SingleUserController@showUser');
    $app->put('/', 'SingleUserController@updateUser');

    $app->get('/modules/', 'SingleUserController@showModules');
    $app->put('/modules/', 'SingleUserController@updateModules');
    $app->post('/modules', 'SingleUserController@addModules');
    $app->delete('/modules', 'SingleUserController@removeModules');

    $app->get('/timetables/', 'SingleUserController@showTimetables');
    $app->delete('/timetables/', 'SingleUserController@removeTimetables');
    $app->post('/timetables/', 'SingleUserController@addTimetable');
    $app->put('/timetables/{id}/', 'SingleUserController@updateTimetable');
  });

  /*users routes*/
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
      $app->get('/', 'UserModulesController@showModules');
      $app->delete('/', 'UserModulesController@removeModules');
      $app->post('/', 'UserModulesController@addModules');
    });

    /*user timetable routes*/
    $app->group([
      'prefix'  =>  '/{user_id}/timetables/'
    ], function() use($app){
      $app->get('/', 'UserTimetablesController@showTimetables');
      $app->delete('/', 'UserTimetablesController@removeTimetables');
      $app->post('/', 'UserTimetablesController@addTimetables');
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
  ], function() use ($app){
    $app->post('/', 'TimetableController@store');
    $app->get('/', 'TimetableController@showAll');
    $app->get('/{timetable_id}', 'TimetableController@show');
    $app->delete('/{module_id}', 'TimetableController@destroy');

    /*timetable event routes*/
    $app->group([
      'prefix'  =>  '/{timetable_id}/events/',
    ], function() use($app){
      $app->get('/', 'TimetableEventsController@showEvents');
      $app->delete('/', 'TimetableEventsController@removeEvents');
      $app->post('/', 'TimetableEventsController@addEvents');
    });

  });


  $app->post('/search/timetables/withModuleDna', 'TimetableController@withModuleDna');
});

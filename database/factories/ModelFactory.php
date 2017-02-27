<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

//User Model factory
$factory->define(App\User::class, function (Faker\Generator $faker) {
  $hasher = app()->make('hash');
  return [
    'email' => $faker->email,
    'password' => $hasher->make('secret')
  ];
});

//Event Model factory
$factory->define(App\Event::class, function (Faker\Generator $faker) {
  return [
    'day' => rand(0,6),
    'language'  => rand(0, 3),
    'group' => rand(1, 6),
    'creator_id'  => rand(1, 10),
    'module_id' => rand(1, 10),
    'name'  => $faker->bothify('???### Lesson #'),
    'date' => $faker->date(),
    'start'  => $faker->time(),
    'end'  => $faker->time()
  ];
});

//Module Model factory
$factory->define(App\Module::class, function(Faker\Generator $faker){
  return [
    'name'  => $faker->word,
    'code'  => $faker->bothify('???###'),
    'description' => $faker->realText(),
    'postgrad'  => $faker->boolean,
    'period'  => $faker->randomElement(['S1','S2','Q1','Q2','Q3','Q4'])
  ];
});

//Timetable Model factory
$factory->define(App\Timetable::class, function(Faker\Generator $faker){
  return [
    'creator_id'  => rand(1,10)
  ];
});

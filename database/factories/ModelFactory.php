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

$factory->define(App\User::class, function (Faker\Generator $faker) {
  $hasher = app()->make('hash');
  return [
    'email' => $faker->email,
    'password' => $hasher->make('secret')
  ];
});

$factory->define(App\Event::class, function (Faker\Generator $faker) {
  return [
    'day' => rand(0,6),
    'language'  => rand(0, 3),
    'group' => rand(0, 6),
    'creator_id'  => rand(0, 10),
    'module_id' => rand(0, 10),
    'name'  => $faker->bothify('???### Lesson #'),
    'date' => $faker->date(),
    'start'  => $faker->time(),
    'end'  => $faker->time()
  ];
});

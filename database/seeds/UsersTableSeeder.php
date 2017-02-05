<?php

use Illuminate\Database\Seeder;
use App\User;

class UserTableSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    //disable foreign key checking because truncate() will fail
    //DB::statement('SET FOREIGN_KEY_CHECKS = 0');

    User::truncate();

    factory(User::class, 10)->create();

    //re-enable foreign key checks
    //DB::statement('SET FOREIGN_KEY_CHECKS = 0');
  }
}

<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

use App\User;


class UserTimetableRoutesTest extends TestCase
{
  use DatabaseTransactions;


  /**
   * I send a POST request to /v1/users/{id}/timetables/ where
   * id is a valid user id with the parameter 'timetables' 
   * containing a json string of an array of valid timetable id's and 
   * the server adds the given timetables to the user and 
   * responds appropriately.
   *
   * (TODO add authentication)
   *
   * @return void
   */
  public function testCanAddTimetables(){
    $this->requestHack();

    $user = $this->getUser();
    $timetables = [1,2,3,4];
    $numTimetables = count($timetables);
    $timetablesJson = json_encode($timetables);

    //detach timetables if they are already used, to test
    //adding them again
    $user->timetables()->detach($timetables);
    $this->actingAs($user)
      ->post(USERS_ROUTE . "/{$user->id}/timetables", [
      'timetables'  => $timetablesJson
    ])->seeStatusCode(self::HTTP_OK)
      ->seeJson([
        'data'  =>  "Added $numTimetables timetable(s) to user."
      ]);
     

    foreach($timetables as $timetable_id){
      $this->seeInDatabase('user_timetables', [
        'timetable_id'  => $timetable_id,
        'user_id'  => $user->id
      ]);
    }
  }

  /**
   * I send a DELETE request to /v1/users/{id}/timetables/ 
   * where id is a valid user id with the parameter 'timetables' 
   * containing a json string of an array of valid timetable id's and 
   * the server remove the selected timetables from the user and
   * responds appropriately.
   * (TODO add authentication)
   *
   * @return void
   */
  public function testCanRemoveTimetables(){
    $this->requestHack();
    $user = $this->getUser();
    $timetables = $user->timetables()->get()
      ->transform(function($timetable, $key){
      return $timetable->id;
    });
    $timetablesArr = $timetables->toArray();
    $timetablesJson = json_encode($timetablesArr);
    $numTimetables = count($timetablesArr);
    $this->actingAs($user)->delete(USERS_ROUTE . "/{$user->id}/timetables", [
      'timetables'  =>  $timetablesJson
    ])->seeStatusCode(self::HTTP_OK)
      ->seeJson([
        'data'  =>  "Removed $numTimetables timetable(s) from user."
      ]);
    
    //check that removed timetables are not present in database
    foreach($timetablesArr as $timetable_id){
      $this->missingFromDatabase('user_timetables', [
        'timetable_id'  => $timetable_id,
        'user_id'  => $user->id
      ]);
    }
  }


  /**
   * I send a GET request to /v1/users/{id}/timetables/ 
   * where id is a valid user id with
   * (TODO add authentication)
   *
   * @return void
   */
  public function testCanShowTimetables(){
    $this->requestHack();
    $user = $this->getUser();
    if($user->timetables()->get()->count() > 0){
      $this->actingAs($user)
        ->get(USERS_ROUTE . "/{$user->id}/timetables")
        ->seeStatusCode(self::HTTP_OK)
        ->seeJsonStructure([
          'data'  => [
            '*' => TIMETABLE_FIELDS 
          ]
        ]);
    }
  }
}

<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

use App\User;

const TIMETABLE_FIELDS = [
  'id', 'hash', 'creator_id',
  'created_at', 'updated_at',
];

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
  public function testCanAddtimetables(){
    $this->get('/');

    $user_id = 1;
    $timetables = [1,2,3,4];
    $numTimetables = count($timetables);
    $timetablesJson = json_encode($timetables);

    $this->post("/v1/users/$user_id/timetables", [
      'timetables'  => $timetablesJson
    ])->seeStatusCode(self::HTTP_OK)
      ->seeJson([
        'data'  =>  "Added $numTimetables timetable(s) to user."
      ]);
     

    foreach($timetables as $timetable_id){
      $this->seeInDatabase('user_timetables', [
        'timetable_id'  => $timetable_id,
        'user_id'  => $user_id
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
  public function testCanRemovetimetables(){
    $this->get('/');
    $id = 1;
    $user = User::findOrFail($id);
    $timetables = $user->timetables();
    $timetables->transform(function($timetable, $key){
      return $timetable->id;
    });
    $timetablesArr = $timetables->toArray();
    $timetablesJson = json_encode($timetablesArr);

    $this->delete("/v1/users/$id/timetables", [
      'timetables'  =>  $timetablesJson
    ])->seeStatusCode(self::HTTP_OK)
      ->seeJson([
        'data'  =>  "Removed $numtimetables timetable(s) from user."
      ]);
    
    //check that removed timetables are not present in database
    foreach($timetablesArr as $timetable_id){
      $this->missingFromDatabase('user_timetables', [
        'timetable_id'  => $timetable_id,
        'user_id'  => $user_id
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
  public function testCanShowtimetables(){
    $this->get('/');

    $id = 1;
    if(User::findOrFail($id)->timetables()->count() > 0){
      $this->get("/v1/users/$id/timetables")
        ->seeStatusCode(self::HTTP_OK)
        ->seeJsonStructure([
          'data'  => [
            '*' => TIMETABLE_FIELDS 
          ]
        ]);
    }
  }
}

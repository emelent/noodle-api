<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

use App\Timetable;
use App\User;

const EVENT_FIELDS = [
  'name', 'day', 'start', 'end',
  'date', 'language', 'group',
  'creator_id', 'module_id', 'created_at',
  'updated_at'
];

class TimetableEventRoutesTest extends TestCase
{
  use DatabaseTransactions;


  /**
   * I send a POST request to /v1/timetables/{id}/events/ where
   * id is a valid timetable id with the parameter 'events' 
   * containing a json string of an array of valid event id's and 
   * the server adds the given events to the timetable and 
   * responds appropriately.
   *
   * (TODO add authentication)
   *
   * @return void
   */
  public function testCanAddEvents(){
    $this->requestHack();

    $timetable_id = 1;
    $user = User::findOrFail(Timetable::findOrFail($timetable_id)->creator_id);
    $events = [1,2,3,4];
    $numEvents = count($events);
    $eventsJson = json_encode($events);

    $this->actingAs($user)
      ->post("/v1/timetables/$timetable_id/events", [
      'events'  => $eventsJson
    ])->seeStatusCode(self::HTTP_OK)
      ->seeJson([
        'data'  =>  "Added $numEvents event(s) to timetable."
      ]);
     

    foreach($events as $event_id){
      $this->seeInDatabase('timetable_events', [
        'event_id'  => $event_id,
        'timetable_id'  => $timetable_id
      ]);
    }
  }

  /**
   * I send a DELETE request to /v1/timetables/{id}/events/ 
   * where id is a valid timetable id with the parameter 'events' 
   * containing a json string of an array of valid event id's and 
   * the server remove the selected events from the timetable and
   * responds appropriately.
   * (TODO add authentication)
   *
   * @return void
   */
  public function testCanRemoveEvents(){
    $this->requestHack();
    $id = 1;
    $timetable = Timetable::findOrFail($id);
    $user = User::findOrFail($timetable->creator_id);
    $eventsArr = [];
    foreach($timetable->events as $event){
      array_push($eventsArr, $event->id);
    }
    $eventsJson = json_encode($eventsArr);
    $numEvents = count($eventsArr);
    $this->actingAs($user)
    ->delete("/v1/timetables/$id/events", [
      'events'  =>  $eventsJson
    ])->seeStatusCode(self::HTTP_OK)
      ->seeJson([
        'data'  =>  "Removed $numEvents event(s) from timetable."
      ]);
    
    //check that removed events are not present in database
    foreach($eventsArr as $event_id){
      $this->missingFromDatabase('timetable_events', [
        'event_id'  => $event_id,
        'timetable_id'  => $id
      ]);
    }
  }


  /**
   * I send a GET request to /v1/timetables/{id}/events/ 
   * where id is a valid timetable id with
   * (TODO add authentication)
   *
   * @return void
   */
  public function testCanShowEvents(){
    $this->get('/');

    $id = 1;
    $timetable = Timetable::findOrFail($id);
    if($timetable->events()->count() > 0){
      $this->actingAs(User::findOrFail($timetable->creator_id))
        ->get("/v1/timetables/$id/events")
        ->seeStatusCode(self::HTTP_OK)
        ->seeJsonStructure([
          'data'  => [
            '*' => EVENT_FIELDS 
          ]
        ]);
    }
  }
}

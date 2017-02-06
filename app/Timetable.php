<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Timetable extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'name', 'day', 'start', 'end',
      'date', 'language', 'group',
      'creator_id', 'module_id'
    ];

    public function creator(){
      return $this->belongsTo('User');
    }

    public function events(){
      return $this->belongsToMany(
        'Event',
        'timetable_events',
        'timetable_id',
        'event_id'
      );
    }

    public function users(){
      return $this->belongsToMany(
        'User',
        'user_timetables',
        'timetable_id',
        'user_id'
      );
    }
}

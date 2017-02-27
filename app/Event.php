<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
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

    public function module(){
      return $this->belongsTo('Module');
    }

    public function timetables(){
      return $this->belongsToMany(
        Timetable::class,
        'timetable_events',
        'event_id',
        'timetable_id'
      );
    }
}

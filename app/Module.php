<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'name', 'description', 'code',
      'type', 'year'
    ];

    public function users(){
      return $this->belongsToMany(
        'User', 
        'user_modules', 
        'module_id',
        'user_id'
      );
    }
}

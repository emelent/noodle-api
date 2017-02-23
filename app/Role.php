<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'name',
    ];

    public function users(){
      return $this->belongsToMany(
        'User',
        'user_roles',
        'role_id',
        'user_id'
      );
    }
}

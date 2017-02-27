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
      'role',
    ];

    public function users(){
      return $this->belongsToMany(
        User::class,
        'user_roles',
        'role_id',
        'user_id'
      );
    }
}

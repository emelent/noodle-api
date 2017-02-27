<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Model implements JWTSubject, AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'email', 'password'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'created_at', 'updated_at'
    ];

    public function modules(){
      return $this->belongsToMany(
        Module::class, 
        'user_modules', 
        'user_id',
        'module_id'
      );
    }

    public function timetables(){
      return $this->belongsToMany(
        Timetable::class, 
        'user_timetables', 
        'user_id',
        'timetable_id'
      );
    }

    public function roles(){
      return $this->belongsToMany(
        Role::class,
        'user_roles',
        'user_id',
        'role_id'
      );
    }

    public function getJWTIdentifier(){
      return $this->getKey();
    }

    public function getJWTCustomClaims(){
      return [];
    }
}

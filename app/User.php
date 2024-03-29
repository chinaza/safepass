<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'fullName', 'email', 'avatar', 'company', 'position', 'skills', 'password'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function teams()
    {
      return $this->hasMany('App\TeamUser');
    }

    public function company()
    {
      return $this->hasOne('App\Company');
    }

    public function pkey()
    {
      return $this->hasOne('App\Pkey');
    }
}

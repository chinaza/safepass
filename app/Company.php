<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{

  protected $fillable = [
      'name', 'email', 'user_id'
  ];

  public function user()
  {
    return $this->belongsTo('App\User');
  }

  public function teams()
  {
    return $this->hasMany('App\Team');
  }
}

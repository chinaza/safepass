<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invitation extends Model
{
  protected $fillable = [
      'email', 'team_id'
  ];
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TeamUser extends Model
{
  protected $fillable = [
      'user_id', 'company_id', 'team_id', 'token', 'role'
  ];

}

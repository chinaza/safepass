<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Password extends Model
{
  protected $fillable = [
      'title', 'imgurl', 'username', 'password', 'company_id', 'team_id', 'url'
  ];
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Password extends Model
{
  protected $fillable = [
      'title', 'imgurl', 'username', 'password', 'iv', 'company_id', 'team_id', 'url'
  ];
}

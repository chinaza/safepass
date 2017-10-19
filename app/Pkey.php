<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pkey extends Model
{
  protected $fillable = [
    'user_id', 'private', 'iv', 'salt', 'public'
  ];
}

<?php

namespace App\Events;

use App\Password;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class PasswordModifiedEvent
{
  use Dispatchable, InteractsWithSockets, SerializesModels;

  public $password;

  /**
  * Create a new event instance.
  *
  * @return void
  */
  public function __construct(Password $password)
  {
    $this->password = $password;
  }
}

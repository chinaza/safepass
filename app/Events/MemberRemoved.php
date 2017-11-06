<?php

namespace App\Events;

use App\TeamUser;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class MemberRemoved
{
  use Dispatchable, InteractsWithSockets, SerializesModels;

  public $member;

  /**
  * Create a new event instance.
  *
  * @return void
  */
  public function __construct(TeamUser $member)
  {
    $this->member = $member;
  }
}

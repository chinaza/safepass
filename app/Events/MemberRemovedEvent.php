<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class MemberRemovedEvent
{
  use Dispatchable, InteractsWithSockets, SerializesModels;

  public $member;

  /**
  * Create a new event instance.
  *
  * @return void
  */
  public function __construct(array $member)
  {
    $this->member = $member;
  }
}

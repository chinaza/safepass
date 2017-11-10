<?php

namespace App\Listeners;

use App\Events\RoleChangedEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Notifications\RoleChangedNotification;

class RoleChangedListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  RoleChanged  $event
     * @return void
     */
    public function handle(RoleChangedEvent $event)
    {
      $user = \App\User::find($event->member->user_id);
      $user->notify(new RoleChangedNotification($event->member));
    }
}

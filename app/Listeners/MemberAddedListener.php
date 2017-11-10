<?php

namespace App\Listeners;

use App\Events\MemberAddedEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Notifications\MemberAddedNotification;

class MemberAddedListener
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
     * @param  MemberAdded  $event
     * @return void
     */
    public function handle(MemberAddedEvent $event)
    {
      $user = \App\User::find($event->member->user_id);
      $user->notify(new MemberAddedNotification($event->member));
    }
}

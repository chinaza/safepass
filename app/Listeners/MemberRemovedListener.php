<?php

namespace App\Listeners;

use App\Events\MemberRemovedEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Notifications\MemberRemovedNotification;
use Illuminate\Support\Facades\Log;

class MemberRemovedListener
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
     * @param  MemberRemoved  $event
     * @return void
     */
    public function handle(MemberRemovedEvent $event)
    {
      $user = \App\User::find($event->member['userId']);
      $user->notify(new MemberRemovedNotification($event->member));
    }
}

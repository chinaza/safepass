<?php

namespace App\Listeners;

use App\User;
use App\Events\PasswordModifiedEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;
use App\Notifications\PasswordModifiedNotification;

class PasswordModifiedListener
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
     * @param  PasswordModified  $event
     * @return void
     */
    public function handle(PasswordModifiedEvent $event)
    {
      $users = User::join('team_users', 'team_users.user_id', '=', 'users.id')
      ->where('team_users.team_id', $event->password->team_id)
      ->get();
      Notification::send($users, new PasswordModifiedNotification($event->password));
    }
}

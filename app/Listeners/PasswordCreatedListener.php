<?php

namespace App\Listeners;

use App\User;
use App\Events\PasswordCreatedEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;
use App\Notifications\PasswordCreatedNotification;
use Illuminate\Support\Facades\Log;

class PasswordCreatedListener
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
  * @param  PasswordCreated  $event
  * @return void
  */
  public function handle(PasswordCreatedEvent $event)
  {
    $users = User::join('team_users', 'team_users.user_id', '=', 'users.id')
    ->where('team_users.team_id', $event->password->team_id)
    ->get();
    Notification::send($users, new PasswordCreatedNotification($event->password));
  }
}

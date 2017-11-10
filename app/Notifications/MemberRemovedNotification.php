<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Team;
use App\Company;

class MemberRemovedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    private $memberIDs;

    /**
    * Create a new notification instance.
    *
    * @return void
    */
    public function __construct($memberIDs)
    {
      $this->memberIDs = $memberIDs;
    }

    /**
    * Get the notification's delivery channels.
    *
    * @param  mixed  $notifiable
    * @return array
    */
    public function via($notifiable)
    {
      return ['mail', 'database'];
    }

    /**
    * Get the mail representation of the notification.
    *
    * @param  mixed  $notifiable
    * @return \Illuminate\Notifications\Messages\MailMessage
    */
    public function toMail($notifiable)
    {
      $company = Company::find($this->memberIDs['companyId']);
      $team = Team::find($this->memberIDs['teamId']);
      return (new MailMessage)
                  ->greeting('Hello!')
                  ->line('You have been removed from ' . $team->name . ', '. $company->name)
                  ->line('Thank you for using SafePass!');
    }

    /**
    * Get the array representation of the notification.
    *
    * @param  mixed  $notifiable
    * @return array
    */
    public function toArray($notifiable)
    {
      $company = Company::find($this->memberIDs['companyId']);
      $team = Team::find($this->memberIDs['teamId']);
      return [
        'teamId' => $team->id,
        'teamName' => $team->name,
        'companyName' => $company->name,
        'msg' => 'You have been removed from ' . $team->name . ', '. $company->name
      ];
    }
}

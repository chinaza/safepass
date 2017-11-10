<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Team;
use App\Company;

class MemberAddedNotification extends Notification  implements ShouldQueue
{
  use Queueable;

  private $member;

  /**
  * Create a new notification instance.
  *
  * @return void
  */
  public function __construct($member)
  {
    $this->member = $member;
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
    $url = url("/teams");
    $company = Company::find($this->member->company_id);
    $team = Team::find($this->member->team_id);

    return (new MailMessage)
                ->greeting('Hello!')
                ->line('You have juut been added to ' . $team->name . ', '. $company->name)
                ->action('Check it out now', $url)
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
    $company = Company::find($this->member->company_id);
    $team = Team::find($this->member->team_id);
    return [
      'teamId' => $this->member->team_id,
      'teamName' => $team->name,
      'companyName' => $company->name,
      'msg' => 'You have been added to ' . $team->name . ', ' . $company->name
    ];
  }
}

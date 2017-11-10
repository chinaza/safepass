<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Log;
use App\Team;
use App\Company;

class PasswordCreatedNotification extends Notification implements ShouldQueue
{
  use Queueable;

  private $password;

  /**
  * Create a new notification instance.
  *
  * @return void
  */
  public function __construct($password)
  {
    $this->password = $password;
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
  * @param  string $url
  * @return \Illuminate\Notifications\Messages\MailMessage
  */
  public function toMail($notifiable)
  {
    $url = url("/passwords/" . $this->password->id);
    $company = Company::find($this->password->company_id);
    $team = Team::find($this->password->team_id);

    return (new MailMessage)
    ->greeting('Hello!')
    ->line('Password for ' . $this->password->title . ' has just been added to '. $team->name .', '. $company->name)
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
    $company = Company::find($this->password->company_id);
    $team = Team::find($this->password->team_id);
    return [
      'passwordId' => $this->password->id,
      'passwordTitle' => $this->password->title,
      'teamName' => $team->name,
      'companyName' => $company->name,
      'type' => 'created',
      'msg' => 'Password for ' . $this->password->title . ' has just been added to '. $team->name .', '. $company->name
    ];
  }
}

<?php

namespace {{App\}}Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class GeneralNotification extends Notification
{
    /**
     * General notification info
     *
     * @var string
     */
    public $info;

    /**
     * Create a notification instance.
     *
     * @param  string  $token
     * @return void
     */
    public function __construct($info)
    {
        $this->info = $info;
    }

    /**
     * Get the notification's channels.
     *
     * @param  mixed  $notifiable
     * @return array|string
     */
    public function via($notifiable)
    {
        return [
            'mail'
        ];
    }

    /**
     * Build the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->line('Hey '.$notifiable->name)
            ->line('We have a notfiication for you.')
            ->line($this->info['title'])
            ->line($this->info['details'])
            ->action('Visit here for more info', url('/user/notifications'));
    }
}
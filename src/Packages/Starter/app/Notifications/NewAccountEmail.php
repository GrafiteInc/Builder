<?php

namespace {{App\}}Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class NewAccountEmail extends Notification
{
    /**
     * The password reset token.
     *
     * @var string
     */
    public $data;

    /**
     * Create a notification instance.
     *
     * @param  string  $token
     * @return void
     */
    public function __construct($event)
    {
        $this->data = $event;
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
        return (new MailMessage())
            ->line('You\'ve got a new account!')
            ->line('EM: '.$this->data->user->email)
            ->line('PW: '.$this->data->password)
            ->line('Click the link below to login')
            ->action('Login', url('login'));
    }
}

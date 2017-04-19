<?php

namespace {{App\}}Listeners;

use {{App\}}Events\UserRegisteredEmail;
use {{App\}}Notifications\NewAccountEmail;
use Illuminate\Support\Facades\Notification;

class UserRegisteredEmailListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     *
     * @param UserRegisteredEmail $event
     */
    public function handle(UserRegisteredEmail $event)
    {
        Notification::send($event->user, new NewAccountEmail($event->password));
    }
}

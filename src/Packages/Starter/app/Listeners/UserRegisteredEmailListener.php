<?php

namespace {{App\}}Listeners;

use {{App\}}Events\UserRegisteredEmail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class UserRegisteredEmailListener
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
     * @param  UserRegisteredEmail  $event
     * @return void
     */
    public function handle(UserRegisteredEmail $event)
    {
        $user = $event->user;
        $password = $event->password;

        Mail::send(
            'emails.new-user',
            ['user' => $user, 'password' => $password],
            function ($m) use ($user) {
                $m->from('info@app.com', 'App');
                $m->to($user->email, $user->name)->subject('You have a new profile!');
            }
        );
    }
}

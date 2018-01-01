<?php

namespace Emails\Listeners;

use Emails\Mail\UserConfirmationMail;
use Core\User\User;
use Core\User\Events\UserRequestConfirmEmail;
use Illuminate\Support\Facades\Mail;

class UserSendConfirmationEmail
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        // ...
    }

    /**
     * Handle the event.
     *
     * @param UserRegistered $event
     * @return void
     */
    public function handle(UserRequestConfirmEmail $event)
    {
        Mail::to($event->user->email)->send(new UserConfirmationMail($event->user));
    }
}
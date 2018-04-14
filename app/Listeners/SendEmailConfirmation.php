<?php

namespace App\Listeners;

use App\Mail\EmailConfirmation;
use Illuminate\Support\Facades\Mail;
use Illuminate\Auth\Events\Registered;

class SendEmailConfirmation
{
    /**
     * Handle the event.
     *
     * @param \Illuminate\Auth\Events\Registered $event
     */
    public function handle(Registered $event)
    {
        Mail::to($event->user)->send(new EmailConfirmation($event->user));
    }
}

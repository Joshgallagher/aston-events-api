<?php

namespace App\Listeners;

use App\Mail\EmailConfirmation;
use Illuminate\Support\Facades\Mail;
use Illuminate\Auth\Events\Registered;

class SendEmailConfirmation
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
     * @param Registered $event
     */
    public function handle(Registered $event)
    {
        Mail::to($event->user)->send(new EmailConfirmation());
    }
}

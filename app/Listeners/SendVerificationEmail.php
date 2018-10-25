<?php

namespace App\Listeners;

use App\Events\UserRegistered;
use App\Jobs\ProcessEmails;
use App\Events\UserRequestedVerificationEmail;
use App\Mail\AccountVerification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendVerificationEmail implements ShouldQueue
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
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        //
        /*
        Mail::send('emails.ticket', $data, function ($message) {

            $message->from('sugbutravel@gmail.com', 'Sugbu Travel');

            $message->to('rtancio@icloud.com')->subject('Welcome to Sugbu Travel');

        });
        */
        Log::info('Sending verification mail for: '.$event->user->email);
        // ProcessEmails::dispatch($event->user->verificationtoken)->onQueue('emails');
        ProcessEmails::dispatch($event->user->verificationtoken);
        // Mail::to($event->user->email)->send(new AccountVerification($event->user->verificationtoken));
    }
}

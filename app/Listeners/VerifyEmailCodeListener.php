<?php

namespace App\Listeners;

use App\Events\VerifyEmailCode;
use App\Mail\EmailVerifyCode;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class VerifyEmailCodeListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(VerifyEmailCode $event): void
    {
        Mail::to($event->user->email)->send(
            new EmailVerifyCode('verify code' , [
                    "Name" => $event->user->name , 
                    "CodeVerify" => $event->user->cod_email ,
                    'Timestamp' => now()
            ])
        );
        
        // dd($event);
    }
}

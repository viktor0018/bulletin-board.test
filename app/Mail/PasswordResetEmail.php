<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Lang;

class PasswordResetEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $link;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($link)
    {
        $this->link = $link;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        return $this->view('emails.password_reset')
        ->subject('Password reset link')  //<= how to pass variable on this subject
        ->with([
            'url'     => $this->link,
        ]);

/*
        return (new MailMessage)
        ->subject(Lang::get('Please verify Email Address'))
        ->line(Lang::get('Please click the button below to verify your email address.'))
        ->action(Lang::get('Verify Email Address'), '')
        ->line(Lang::get('If you did not create an account, no further action is required.'))
        ->markdown('emails.email_verification', [
            'url' => $this->link,
        ]);
        */
    }
}

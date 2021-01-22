<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendEmailTest extends Mailable
{
    use Queueable, SerializesModels;

    public $name;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->name = 123;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
            return $this->view('emails.test')
                    ->subject('this is test email subject')  //<= how to pass variable on this subject
                    ->with([
                        'title'     => 'tit', //this works without queue
                        'content'     => 'con', //this works without queue
                    ]);
    }
}

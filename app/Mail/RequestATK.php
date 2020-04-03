<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class RequestATK extends Mailable
{
    use Queueable, SerializesModels;
    public $customSubject, $req_atk;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($customSubject,$req_atk)
    {
        $this->customSubject = $customSubject;
        $this->req_atk = $req_atk;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->customSubject)->view('mail.MailRequestATK');
    }
}

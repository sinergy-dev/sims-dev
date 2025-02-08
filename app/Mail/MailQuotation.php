<?php


namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MailQuotation extends Mailable
{
    use Queueable,SerializesModels;
    public $customSubject, $detail, $receiver, $status, $notes, $config;

    public function __construct($customSubject, $detail, $receiver, $status, $notes, $config)
    {
        $this->customSubject = $customSubject;
        $this->detail = $detail;
        $this->receiver = $receiver;
        $this->status = $status;
        $this->notes = $notes;
        $this->config = $config;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->customSubject)->view('mail.MailQuotation');
    }
}
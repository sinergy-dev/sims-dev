<?php


namespace App\Mail;


use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
class MailIdeaHub extends Mailable
{
    use Queueable,SerializesModels;
    public $customSubject, $detail, $receiver, $sender;

    public function __construct($customSubject, $detail, $receiver, $sender)
    {
        $this->customSubject = $customSubject;
        $this->detail = $detail;
        $this->receiver = $receiver;
        $this->sender = $sender;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->customSubject)->view('mail.MailIdeaHub');
    }
}
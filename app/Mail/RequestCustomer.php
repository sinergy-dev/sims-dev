<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RequestCustomer extends Mailable
{
    use Queueable, SerializesModels;
    public $customSubject,$data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($customSubject,$data)
    {
        $this->customSubject = $customSubject;
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->customSubject.' - '.$this->data->customer_legal_name)
                    ->view('mail.MailRequestCustomer');
    }
}

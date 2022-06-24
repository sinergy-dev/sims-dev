<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailChangeCustomer extends Mailable
{
    use Queueable, SerializesModels;
    public $data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // return $this->data["type"];
        if ($this->data["type"] == 'Change Nominal') {
            return $this->subject('[SIMS-App] Request Change - Nominal Update Lead Register')
            ->view('mail.MailChangeNominal');
        } else {
            return $this->subject('[SIMS-App] Request Change - Customer Update Lead Register')
            ->view('mail.MailChangeNominal');
        }
    }
}

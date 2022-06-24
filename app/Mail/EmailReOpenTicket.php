<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailReOpenTicket extends Mailable
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
        //
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('[SIMS-App] Request Change - Re-Open Ticket')
            ->view('mail.MailReOpenTicket');

        // return $this->subject('[SIMS-App] Request Change - Nominal Update Lead Register WIN')
        //     ->view('mail.MailChangeNominal');
    }
}

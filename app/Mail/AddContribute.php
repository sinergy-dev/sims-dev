<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AddContribute extends Mailable
{
    use Queueable, SerializesModels;
    public $data,$status;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data,$status)
    {
        //
        $this->data = $data;
        $this->status = $status;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Add Contribute'.' - '.$this->data->lead_id)
                    ->view('mail.MailAddContribute');
    }
}

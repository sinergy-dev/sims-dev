<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReminderUpdateTimesheet extends Mailable
{
    use Queueable, SerializesModels;
    public $all;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($all)
    {
        //
        $this->all = $all;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject("[SIMS-App] Reminder Email - Please Update Your Timesheet's Status")
            ->view('mail.MailReminderTimesheet');
    }
}

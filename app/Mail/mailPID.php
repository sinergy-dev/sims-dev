<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class mailPID extends Mailable
{
    use Queueable, SerializesModels;
    public $pid_info;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($pid_info)
    {
        //
        $this->pid_info = $pid_info;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if ($this->pid_info->lead_id == 'MSPQUO' || $this->pid_info->lead_id == 'MSPPO') {
            return $this->subject('Request PID Succces [' . $this->pid_info->id_project . ' - ' . $this->pid_info->no_po_customer . ']')
                    ->view('mail.MailPIDSales');
        }else{
            return $this->subject('Request PID Succces [' . $this->pid_info->id_project . ' - ' . $this->pid_info->lead_id . ']')
                    ->view('mail.MailPIDSales');
        }
        
    }
}

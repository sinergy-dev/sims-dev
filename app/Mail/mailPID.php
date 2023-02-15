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
    public $users,$status,$getPmManager;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($pid_info,$users,$getPmManager,$status)
    {
        //
        $this->pid_info = $pid_info;
        $this->users = $users;
        $this->status = $status;
        $this->getPmManager = $getPmManager;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if ($this->status == 'sales') {
                if ($this->pid_info->lead_id == 'MSPQUO' || $this->pid_info->lead_id == 'MSPPO') {
                return $this->subject('Request PID Succces [' . $this->pid_info->id_project . ' - ' . $this->pid_info->no_po_customer . ']')
                        ->view('mail.MailPIDSales');
            } else{
                return $this->subject('Request PID Succces [' . $this->pid_info->id_project . ' - ' . $this->pid_info->lead_id . ']')
                        ->view('mail.MailPIDSales');
            }
        } else {
            return $this->subject('New Project ID [' . $this->pid_info->id_project . ' - ' . $this->pid_info->lead_id . ']')
                        ->view('mail.MailPIDSales');
        }
    }
}

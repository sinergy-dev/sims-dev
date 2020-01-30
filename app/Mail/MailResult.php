<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class MailResult extends Mailable
{
    use Queueable, SerializesModels;
    public $users,$pid_info;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($users,$pid_info)
    {
        //
        $this->users = $users;
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
            return $this->subject('Permohonan Pembuatan Project ID'.' - '.$this->pid_info->no_po)
                    ->view('mail.MailResult');
        }else{
            return $this->subject('Permohonan Pembuatan Project ID'.' -  '.$this->pid_info->lead_id)
                    ->view('mail.MailResult');
        }
        
    }
}

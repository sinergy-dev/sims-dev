<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DraftPR extends Mailable
{
    use Queueable, SerializesModels;
    public $detail,$kirim_user,$customSubject,$detail_approver,$next_approver;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($detail,$kirim_user,$customSubject,$detail_approver,$next_approver)
    {
        $this->detail = $detail;
        $this->kirim_user = $kirim_user;
        $this->customSubject = $customSubject;
        $this->detail_approver = $detail_approver;
        $this->next_approver = $next_approver;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->customSubject)->view('mail.MailDraftPr');
    }
}

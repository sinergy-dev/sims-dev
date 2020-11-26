<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class PeminjamanAssetMSM extends Mailable
{
    use Queueable, SerializesModels;
     public $peminjaman,$admin,$customSubject;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($peminjaman,$admin,$customSubject)
    {
        //
        $this->peminjaman = $peminjaman;
        $this->admin = $admin;
        $this->customSubject = $customSubject;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
         return $this->subject($this->customSubject)
                    ->view('mail.MailPeminjamanAsset');
    }
}

<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class AcceptPinjamanAssetMSM extends Mailable
{
    use Queueable, SerializesModels;
    public $peminjaman,$users,$barang,$total_barang,$customSubject;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($peminjaman,$users,$barang,$total_barang,$customSubject)
    {
        //
        $this->peminjaman = $peminjaman;
        $this->users = $users;
        $this->barang = $barang;
        $this->total_barang = $total_barang;
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
                    ->view('mail.MailAcceptPeminjamanMSM');
    }
}

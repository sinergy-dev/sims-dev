<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class MailReminderPengembalianAssetTech extends Mailable
{
    use Queueable, SerializesModels;
    public $max,$users,$peminjaman,$total_barang,$barang,$customSubject;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($max,$users,$peminjaman,$total_barang,$barang,$customSubject)
    {
        //
        $this->max = $max;
        $this->users = $users;
        $this->peminjaman = $peminjaman;
        $this->total_barang = $total_barang;
        $this->barang = $barang;
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
                    ->view('mail.MailReminderPengembalianAssetTech');
    }
}

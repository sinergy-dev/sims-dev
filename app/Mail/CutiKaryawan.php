<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class CutiKaryawan extends Mailable
{
    use Queueable, SerializesModels;
    public $name_cuti,$hari,$ardetil;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($name_cuti,$hari,$ardetil)
    {
        //
        $this->name_cuti = $name_cuti;
        $this->hari = $hari;
        $this->ardetil = $ardetil;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        return $this->subject('Cuti Karyawan')
                    ->view('mail.MailCutiKaryawan');
    }
}

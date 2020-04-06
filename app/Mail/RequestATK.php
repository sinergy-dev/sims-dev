<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class RequestATK extends Mailable
{
    use Queueable, SerializesModels;
    public $customSubject, $req_atk,$sebuah_variable,$id_position;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($customSubject,$req_atk,$id_position)
    {
        $this->customSubject = $customSubject;
        $this->req_atk = $req_atk;
        $this->id_position = $id_position;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if($this->id_position == "HR MANAGER" || $this->id_position == 'STAFF GA'){
            $this->sebuah_variable = "Berikut request ATK yang harus dibuatkan PR:";
        } else {
            $this->sebuah_variable = "Request ATK mu sedang dibuatkan PR, tunggu informasi lebih lanjut, berikut rinciannya:";
        }
        return $this->subject($this->customSubject)->view('mail.MailRequestATK');
    }
}

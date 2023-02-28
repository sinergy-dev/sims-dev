<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class RequestATK extends Mailable
{
    use Queueable, SerializesModels;
    public $customSubject, $req_atk, $sebuah_variable, $id_position, $id_division, $get_user,$status,$status_barang;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($customSubject,$req_atk,$id_position,$id_division,$get_user,$status,$status_barang)
    {
        $this->customSubject = $customSubject;
        $this->req_atk = $req_atk;
        $this->id_position = $id_position;
        $this->id_division = $id_division;
        $this->get_user = $get_user;
        $this->status = $status;
        $this->status_barang = $status_barang;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if($this->id_position == "HR MANAGER" || $this->id_division == 'PROCUREMENT'){
            if ($this->status_barang == 'LOGISTIK') {
                $this->sebuah_variable = "Berikut request Logistik yang harus dibuatkan PR:";
            } else {
                $this->sebuah_variable = "Berikut request ATK yang harus dibuatkan PR:";
            }
            
        } else {
            if ($this->status_barang == 'LOGISTIK') {
                $this->sebuah_variable = "Request Logistik mu sedang dibuatkan PR, tunggu informasi lebih lanjut, berikut rinciannya:";
            } else {
                $this->sebuah_variable = "Request ATK mu sedang dibuatkan PR, tunggu informasi lebih lanjut, berikut rinciannya:";
            }
            
        }
        if ($this->status == 'PENDING' || $this->status == 'REQUEST') {
            return $this->subject($this->customSubject)->view('mail.MailRequestNewATK');
        } else {
            return $this->subject($this->customSubject)->view('mail.MailRequestATK');    
        }
    }
}

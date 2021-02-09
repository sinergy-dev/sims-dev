<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class RequestAssetHr extends Mailable
{
    use Queueable, SerializesModels;
    public $status,$users,$req_asset,$subject;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($status,$users,$req_asset,$subject)
    {
        //
        $this->status        = $status;
    	$this->users		= $users;
    	$this->req_asset	= $req_asset;
    	$this->subject 		= $subject;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if ($this->status == 'peminjaman') {
            if ($this->req_asset->status == "PENDING" || $this->req_asset->status == "AVAILABLE") {
                return $this->subject($this->subject)
                ->view('mail.MailRequestPeminjamanAsset');
            }else if($this->req_asset->status == "ACCEPT"){
                return $this->subject($this->subject)
                ->view('mail.MailAcceptRequestAsset');
            }else if($this->req_asset->status == "REJECT"){
                return $this->subject($this->subject)
                ->view('mail.MailAcceptRequestAsset');
            }
        }else if ($this->status == 'new') {
            return $this->subject($this->subject)
            ->view('mail.MailRequestNewAsset');  
        }else if ($this->status == 'proses') {
            if($this->req_asset->status == "PENDING"){
                return $this->subject($this->subject)
                ->view('mail.MailAcceptRequestAsset');
            }else{
                return $this->subject($this->subject)
                ->view('mail.MailAcceptRequestAsset');
            }
        } 
            
        
    	
        
    }
}

<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class RequestNewAssetHr extends Mailable
{
    use Queueable, SerializesModels;
    public $users,$req_asset,$subject;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($users,$req_asset,$subject)
    {
        //
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
    	if ($this->req_asset->status == "PENDING") {
    		return $this->subject($this->subject)
        	->view('mail.MailRequestPeminjamanAsset');
    	}else if($this->req_asset->status == "ACCEPT"){
    		return $this->subject($this->subject)
        	->view('mail.MailAcceptRequestAsset');
    	}
        
    }
}

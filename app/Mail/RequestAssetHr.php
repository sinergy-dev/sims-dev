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
        $this->status       = $status;
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
        if ($this->status == 'new') {
            return $this->subject($this->subject)
            ->view('mail.MailRequestNewAsset');  
        }else {
            return $this->subject($this->subject)
            ->view('mail.MailAcceptRequestAsset');
        }  
    }
}

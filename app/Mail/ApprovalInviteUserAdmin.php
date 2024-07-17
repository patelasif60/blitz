<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ApprovalInviteUserAdmin extends Mailable
{
    use Queueable, SerializesModels;
    public $user;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user)
    {
        //
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        //@InvitesentbyName 
        if($this->user['user']->user_type == "Approver") {
            return $this->subject($this->user['mainUser']['firstname'] . ' ' . $this->user['mainUser']['lastname']. ' telah mengundang Anda sebagai pemberi persetujuan di portal blitznet')
            ->markdown('emails.customer.approvalInviteUserAdmin')
            ->with('user', $this->user);
        } else {
            return $this->subject($this->user['mainUser']['firstname'] . ' ' . $this->user['mainUser']['lastname']. ' telah mengundang Anda sebagai konsultan di portal blitznet')
            ->markdown('emails.customer.approvalInviteUserAdmin')
            ->with('user', $this->user);
        }
        
    }
}

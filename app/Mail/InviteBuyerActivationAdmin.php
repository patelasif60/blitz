<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InviteBuyerActivationAdmin extends Mailable
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
        $this->user = $user;

    }

    /**
     * Build the message.
     * 3
     *
     * @return $this
     */
    public function build()
    {
        //return $this->markdown('emails.admin.inviteBuyerActivationAdmin');
        return $this->subject($this->user['user']->contact_person_name. ' mengundang Anda bergabung di Portal blitznet.' )
            ->markdown('emails.admin.inviteBuyerActivationAdmin')
            ->with('user', $this->user);
    }
}

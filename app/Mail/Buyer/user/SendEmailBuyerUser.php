<?php

namespace App\Mail\Buyer\user;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class SendEmailBuyerUser extends Mailable
{
    use Queueable, SerializesModels;

    protected $user;
    protected $password;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $password)
    {
        $this->user = $user;
        $this->password = $password;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $user = $this->user;
        $name = $user->full_name;

        return $this->subject($name. ' Terkirim telah mengundang anda untuk gabung Blitznet Portal.')
            ->markdown('buyer.emails.SendEmailBuyerUser')
            ->with('user', $user)->with('password', $this->password);
    }
}

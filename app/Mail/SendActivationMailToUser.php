<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendActivationMailToUser extends Mailable
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
        return $this->subject('Aktivasi Akun')
        ->markdown('dashboard.email.sendActivationMailToUser_indo')
        ->with('user', $this->user);
        /* ->attach($this->order['pdf'], [
         'as' => 'order.pdf',
         'mime' => 'application/pdf',
        ]); */
    }
}

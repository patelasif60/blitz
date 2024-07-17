<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class QuoteApprovalMail extends Mailable
{
    use Queueable, SerializesModels;
    public $userData;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user)
    {
        $this->userData = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        //if($this->userData['user']->user_type == "Approver") {
            return $this->subject('Persetujuan Penawaran Harga untuk '.$this->userData['quote']->quote_number)
            ->markdown('emails.customer.QuoteApprovalMail')
            ->with('userData', $this->userData);
        // } else {
        //     return $this->subject('Persetujuan Penawaran Harga untuk '.$this->userData['quote']->quote_number.' Received')
        //     ->markdown('emails.customer.QuoteApprovalMail')
        //     ->with('userData', $this->userData);
        // }
    }
}

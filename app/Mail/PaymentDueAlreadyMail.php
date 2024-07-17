<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PaymentDueAlreadyMail extends Mailable
{
    use Queueable, SerializesModels;
    public $payment_already_due;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($payment_already_due)
    {
        $this->payment_already_due = $payment_already_due;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.customer.paymentDueAlreadyMail')->with('payment_already_due', $this->payment_already_due);
    }
}

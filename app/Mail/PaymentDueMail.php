<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PaymentDueMail extends Mailable
{
    use Queueable, SerializesModels;
    public $payment_due;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($payment_due)
    {
        $this->payment_due = $payment_due;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Pembayaran Jatuh tempo '. $this->payment_due['order_no'])->markdown('emails.customer.paymentDuemail')->with('payment_due', $this->payment_due);
    }
}

<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendQuoteToBuyer extends Mailable
{
    use Queueable, SerializesModels;
    public $quote;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($quote)
    {
        $this->quote = $quote;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Quotation untuk ' . $this->quote['quote']->reference_number . ' anda di terima')
            ->markdown('admin.email.sendQuoteToBuyer_indo')
            ->with('quote', $this->quote);
    }
}

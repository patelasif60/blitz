<?php

namespace App\Mail;

use App\Models\Rfq;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendRfqToBuyer extends Mailable
{
    use Queueable, SerializesModels;
    public $rfq;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Rfq $rfq)
    {
        $this->rfq = $rfq;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('RFQ Diterima ' . $this->rfq->reference_number)
                    ->markdown('dashboard.email.sendRfqToBuyer_indo')
                    ->with('rfq', $this->rfq)
                    ->with('rfqProducts', $this->rfq->rfqProducts()->get());
    }
}

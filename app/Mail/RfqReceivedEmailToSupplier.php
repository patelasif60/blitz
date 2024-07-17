<?php

namespace App\Mail;

use App\Models\Rfq;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RfqReceivedEmailToSupplier extends Mailable
{
    use Queueable, SerializesModels;
    public $rfq;
    public $supplierName;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Rfq $rfq,$supplierName)
    {
        $this->rfq = $rfq;
        $this->supplierName = $supplierName;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('RFQ Diterima')
            ->markdown('emails.supplier.rfqReceivedEmailToSupplier')
            ->with('rfq', $this->rfq)
            ->with('supplierName', $this->supplierName)
            ->with('rfqProducts', $this->rfq->rfqProducts()->get());
    }
}

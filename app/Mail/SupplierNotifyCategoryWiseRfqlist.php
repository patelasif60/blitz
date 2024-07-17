<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SupplierNotifyCategoryWiseRfqlist extends Mailable
{
    use Queueable, SerializesModels;
    public $rfqsDetails;
    public $supplierDetails;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($rfqsDetails,$supplierDetails)
    {
        $this->rfqsDetails = $rfqsDetails;
        $this->supplierDetails = $supplierDetails;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject( 'Rfq List for deling category')
            ->markdown('emails.supplier.supplierNotifyCategoryWiseRfqlist')
            ->with('rfqsDetails', $this->rfqsDetails)
            ->with('supplierDetails', $this->supplierDetails);
    }
}

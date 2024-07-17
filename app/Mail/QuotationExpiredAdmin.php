<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class QuotationExpiredAdmin extends Mailable
{
    use Queueable, SerializesModels;
    public $days;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->days = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Quotation '. $this->days['quote_number'].' untuk '. $this->days['rfq_id'] .' sudah tidak berlaku.')->markdown('emails.admin.quotationExpiredAdmin')->with('days', $this->days);
    }
}

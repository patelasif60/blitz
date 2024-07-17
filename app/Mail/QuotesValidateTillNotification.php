<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class QuotesValidateTillNotification extends Mailable
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
        return $this->subject('Quotation untuk '. $this->days['rfq_id'] .' akan berakhir ')->markdown('emails.customer.quotesValidatMail')->with('days', $this->days);
    }
}

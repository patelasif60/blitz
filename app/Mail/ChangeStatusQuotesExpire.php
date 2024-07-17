<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ChangeStatusQuotesExpire extends Mailable
{
    use Queueable, SerializesModels;
    public $quote_expire;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
       $this->quote_expire = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Masa Berlaku Quotation '. $this->quote_expire['quote_number'] .' untuk '. $this->quote_expire['rfq_id'] .' telah berakhir')->markdown('emails.customer.quotesExpireStatus')->with('quote_expire', $this->quote_expire);
    }
}

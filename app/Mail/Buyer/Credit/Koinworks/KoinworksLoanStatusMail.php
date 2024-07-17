<?php

namespace App\Mail\Buyer\Credit\Koinworks;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class KoinworksLoanStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    private $data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject( 'Loan Repayment for '.$this->data->loan_application_number )
            ->markdown('emails.credit.limit.loanRepaymentStatus')
            ->with('data', $this->data);
    }
}

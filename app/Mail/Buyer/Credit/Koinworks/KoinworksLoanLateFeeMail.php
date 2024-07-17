<?php

namespace App\Mail\Buyer\Credit\Koinworks;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class KoinworksLoanLateFeeMail extends Mailable
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
        return $this->subject( 'Overdue fee is applied on Loan '.$this->data->loan_number)
            ->markdown('emails.credit.limit.loanLAteFeeApply')
            ->with('data', $this->data);
    }
}

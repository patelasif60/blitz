<?php

namespace App\Mail\Buyer\Credit\Koinworks;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class KoinworksLoanFeeReminderMail extends Mailable
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
        return $this->subject( 'Your loan '.$this->data->loan_number.' due date will be in 14 days.')
            ->markdown('emails.credit.limit.loanLateFeeReminder')
            ->with('data', $this->data);
    }
}

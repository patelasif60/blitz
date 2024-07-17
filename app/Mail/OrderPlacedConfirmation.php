<?php

namespace App\Mail;

use App\Models\BankDetails;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderPlacedConfirmation extends Mailable
{
    use Queueable, SerializesModels;
    public $order;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($order)
    {
        //
        $this->order = $order;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Pesanan Terkonfirmasi ' . $this->order->order_number)
            ->markdown('dashboard.email.orderPlacedConfirmation_indo')
            ->with('order', $this->order)
            ->with('rfq', $this->order->rfq()->first())
            ->with('quoteItems', $this->order->quote->quoteItems()->get());
    }
}

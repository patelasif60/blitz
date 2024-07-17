<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderPlacedConfirmationToSupplier extends Mailable
{
    use Queueable, SerializesModels;
    public $order;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Order dikonfirmasi ' . $this->order->order_number)
            ->markdown('dashboard.email.orderPlacedConfirmationToSupplier_indo')
            ->with('order', $this->order)
            ->with('quoteItems', $this->order->quote->quoteItems()->get())
            ->with('supplier', $this->order->supplier()->first(['contact_person_name']));
    }
}

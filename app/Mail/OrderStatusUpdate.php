<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderStatusUpdate extends Mailable
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

        $statusName = __('order.'.trim($this->order->orderStatus->name));
        if($this->order->order_status==8)
            $statusName = $this->order->payment_due_date?sprintf($statusName,changeDateFormat($this->order->payment_due_date,'d/m/Y')):sprintf($statusName,'DD/MM/YYYY');;

        return $this->subject('Update Status Order ' . $this->order->order_number)
                    ->markdown('admin.email.orderStatusUpdate_indo')
                    ->with('order', $this->order)
                    ->with('statusName', $statusName)
                    ->with('rfq', $this->order->rfq()->first())
                    ->with('quoteItems', $this->order->quote->quoteItems()->get());
    }
}

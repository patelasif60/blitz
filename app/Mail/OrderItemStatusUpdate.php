<?php

namespace App\Mail;

use App\Models\OrderItem;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderItemStatusUpdate extends Mailable
{
    use Queueable, SerializesModels;
    public $orderItem;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(OrderItem $orderItem)
    {
        $this->orderItem = $orderItem;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        $statusName = __('order.'.trim($this->orderItem->orderItemStatus->name));
        return $this->subject('Update Status Order ' . $this->orderItem->order_number)
            ->markdown('admin.email.order_item_status_update_buyer')
            ->with('orderItem', $this->orderItem)
            ->with('statusName', $statusName)
            ->with('rfq', $this->orderItem->order->rfq()->first(['firstname','lastname']))
            ->with('quoteItem', $this->orderItem->quoteItem()->first());
    }
}

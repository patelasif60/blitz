<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CreditStatusToBuyer extends Mailable
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
        $view = 'admin.email.creditRejectedToBuyer';
        $subject = 'Pengajuan kredit tertolak untuk ' . $this->order->order_number;
        if ($this->order->is_credit){
            $view = 'admin.email.creditApprovedToBuyer';
            $subject = 'Pengajuan kredit diterima untuk ' . $this->order->order_number;
        }
        return $this->subject($subject)
            ->markdown($view)
            ->with('order', $this->order)
            ->with('rfq', $this->order->rfq()->first());
    }
}

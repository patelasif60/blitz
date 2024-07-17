<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendPoToSupplier extends Mailable
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
        $this->order = $order;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Order Di Konfirmasi ' . $this->order['order']->order_number)
            ->markdown('admin.email.sendPoToSupplier_indo')
            ->with('order', $this->order)
            ->attach($this->order['pdf'], [
                'as' => $this->order['order']->po_number . '.pdf',
                'mime' => 'application/pdf',
            ]);
    }
}

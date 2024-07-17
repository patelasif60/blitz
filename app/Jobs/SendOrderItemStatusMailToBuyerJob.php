<?php

namespace App\Jobs;

use App\Mail\OrderItemStatusUpdate;
use App\Models\OrderItem;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendOrderItemStatusMailToBuyerJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $orderItem;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(OrderItem $orderItem)
    {
        //
        $this->orderItem = $orderItem;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $bccUsers = \Config::get('static_arrays.bccusers');
        Mail::to($this->orderItem->order->rfq->email)->bcc($bccUsers)->queue(new OrderItemStatusUpdate($this->orderItem));
    }
}

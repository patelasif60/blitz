<?php

namespace App\Jobs;

use App\Mail\OrderItemStatusUpdateToSupplier;
use App\Models\OrderItem;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendOrderItemStatusMailToSupplierJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $orderItem;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(OrderItem $orderItem)
    {
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
        $supplier = $this->orderItem->order->supplier()->first(['contact_person_email', 'alternate_email']);
        Mail::to($supplier->contact_person_email)->cc($supplier->alternate_email)->bcc($bccUsers)->queue(new OrderItemStatusUpdateToSupplier($this->orderItem));
    }
}

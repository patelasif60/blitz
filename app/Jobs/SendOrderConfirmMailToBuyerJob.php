<?php

namespace App\Jobs;

use App\Mail\OrderPlacedConfirmation;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendOrderConfirmMailToBuyerJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $orderData;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Order $orderData)
    {
        $this->orderData = $orderData;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $bccUsers = \Config::get('static_arrays.bccusers');
        Mail::to($this->orderData->rfq->email)->bcc($bccUsers)->queue(new OrderPlacedConfirmation($this->orderData));
    }
}

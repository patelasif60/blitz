<?php

namespace App\Jobs;

use App\Mail\CreditStatusToBuyer;
use App\Mail\OrderStatusUpdate;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendOrderStatusMailToBuyerJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $orderData;
    public $isCreditApprovedReject;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Order $orderData,$isCreditApprovedReject=0)
    {
        $this->orderData = $orderData;
        $this->isCreditApprovedReject = $isCreditApprovedReject;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $bccUsers = \Config::get('static_arrays.bccusers');
        if ($this->isCreditApprovedReject!=1) {
            Mail::to($this->orderData->rfq->email)->bcc($bccUsers)->queue(new OrderStatusUpdate($this->orderData));
        }else{
            Mail::to($this->orderData->rfq->email)->bcc($bccUsers)->queue(new CreditStatusToBuyer($this->orderData));
        }
    }
}

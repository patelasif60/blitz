<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use App\Mail\SendPoToSupplier;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendPoToSupplierJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $orderData;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($orderData)
    {
        $this->orderData = $orderData;
        //dd($this->orderData['email']);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to($this->orderData['email'])->queue(new SendPoToSupplier($this->orderData));
    }
}

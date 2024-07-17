<?php

namespace App\Jobs;

use App\Mail\RfqReceivedEmailToSupplier;
use App\Mail\SendRfqToBuyer;
use App\Models\Rfq;
use App\Models\Unit;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class RfqReceivedEmailBuyerJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $rfq = Rfq::find($this->id);
        $ccUsers = \Config::get('static_arrays.bccusers');
        Mail::to($rfq->email)->bcc($ccUsers)->send(new SendRfqToBuyer($rfq));
    }
}


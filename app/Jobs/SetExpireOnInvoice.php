<?php

namespace App\Jobs;

use App\Http\Controllers\Payment\XenditController;
use App\Models\OrderTransactions;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SetExpireOnInvoice implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $invoiceData;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($invoiceData)
    {
        $this->invoiceData = $invoiceData;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $xendit = new XenditController;
        $xendit->expireInvoice($this->invoiceData);
    }
}

<?php

namespace App\Jobs\buyer\Credit\KoinWorks;

use App\Http\Controllers\Buyer\Xendit\LoanTransaction\XenditLoanTransactionController;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class KoinworksLoanLateFeeJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        (new XenditLoanTransactionController())->syncLoanLateFeeCharges();
    }
}

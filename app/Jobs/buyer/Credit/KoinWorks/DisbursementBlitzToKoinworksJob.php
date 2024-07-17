<?php

namespace App\Jobs\buyer\Credit\KoinWorks;

use App\Http\Controllers\Buyer\Xendit\LoanTransaction\XenditLoanTransactionController;
use App\Models\LoanApply;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class DisbursementBlitzToKoinworksJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $loan;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($loan)
    {
        $this->loan = $loan;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        (new XenditLoanTransactionController())->disbursementToKoinworks($this->loan);
        (new XenditLoanTransactionController())->internalTransferToBlitznet($this->loan);
    }
}

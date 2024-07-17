<?php

namespace App\Jobs\Credit\KoinWorks;

use App\Http\Controllers\Buyer\Xendit\LoanTransaction\XenditLoanTransactionController;
use App\Models\LoanApply;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SupplierDisbursementJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $loan;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(LoanApply $loan)
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
        $xenditLoanTransController = new XenditLoanTransactionController;
        $xenditLoanTransController->supplierDisbursement($this->loan);
    }
}

<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Http\Controllers\Buyer\Credit\LoanController;
use App\Models\LoanApply;

class KoinworksRepaidLoanCheckJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $loanApply;
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
        $loan = new LoanController();
        $loan->loanRepaymentCheck();
    }
}

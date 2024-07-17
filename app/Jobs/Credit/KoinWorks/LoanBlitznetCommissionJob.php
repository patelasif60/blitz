<?php

namespace App\Jobs\Credit\KoinWorks;

use App\Http\Controllers\Buyer\Xendit\LoanTransaction\XenditLoanTransactionController;
use App\Models\Order;
use App\Models\PaymentProviderAccount;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class LoanBlitznetCommissionJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $order;
    public $sourceUser;
    public $commission;

    /**
     * Create a new job instance.
     *
     * @param Order $order
     * @param PaymentProviderAccount $sourceUser
     * @param $commission
     */
    public function __construct(Order $order,PaymentProviderAccount $sourceUser,$commission)
    {
        $this->order = $order;
        $this->sourceUser = $sourceUser;
        $this->commission = $commission;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $xenditLoanTransController = new XenditLoanTransactionController;
        $xenditLoanTransController->blitznetCommission($this->order,$this->sourceUser,$this->commission);
    }
}

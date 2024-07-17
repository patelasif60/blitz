<?php

namespace App\Jobs;

use App\Http\Controllers\Payment\XenditController;
use App\Models\SupplierTransactionCharge;
use App\Models\XenBalanceTransfer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SupplierTransactionFeesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $supplierId;
    public $disbursementId;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($supplierId,$disbursementId)
    {
        $this->supplierId = $supplierId;
        $this->disbursementId = $disbursementId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $supplierId = $this->supplierId;
        $supplierTransactionCharge = (int)getSettingValueByKey('supplier_transaction_charge');
        $btData = [
            'reference' => 'SPTRF-'.date('mY').$supplierId,
            'amount' => $supplierTransactionCharge,
            'source_user_id' => getXenPlatformIdBySupplierId($supplierId),
        ];
        $xendit = new XenditController;
        $result = $xendit->balanceTransfer($btData);
        $result['supplier_id'] = $supplierId;
        $xenBalResult = XenBalanceTransfer::createOrUpdateXenBalance($result);
        $stcData = [
            'supplier_id'=>$supplierId,
            'disbursement_id'=>$this->disbursementId,
            'xen_transfer_id'=>$xenBalResult->id,
            'paid_date'=>date('Y-m-d'),
            'paid_amount'=>$supplierTransactionCharge
        ];
        SupplierTransactionCharge::createOrUpdateCharge($stcData);
    }
}

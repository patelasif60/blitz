<?php

namespace App\Jobs;

use App\Http\Controllers\Payment\XenditController;
use App\Models\BlitznetCommission;
use App\Models\CommissionType;
use App\Models\Disbursements;
use App\Models\Order;
use App\Models\XenBalanceTransfer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class BlitznetCommissionJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $data;
    public $paymentType;
    public $commissionPer;
    public $commission;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data,$commission,$paymentType=1,$commissionPer=null)
    {
        $this->data = $data;
        $this->paymentType = $paymentType;
        $this->commissionPer = $commissionPer??null;
        $this->commission = $commission;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
            $supplierId = $this->data['supplier_id'];
            $btData = [
                'reference' => $this->data['reference'],
                'amount' => $this->commission,
                'source_user_id' => getXenPlatformIdBySupplierId($supplierId),
            ];
            $xendit = new XenditController;
            $result = $xendit->balanceTransfer($btData);
            $result['supplier_id'] = $supplierId;
            $xenBalResult = XenBalanceTransfer::createOrUpdateXenBalance($result);
            $stcData = [
                'group_id' => $this->data['group_id']??null,
                'order_id' => $this->data['order_id']??null,
                'supplier_id' => $supplierId??null,
                'disbursement_id' => $this->data['disbursement_id']??null,
                'xen_balance_transfer_id' => $xenBalResult->id,
                'commission_type_id' => $this->data['commission_type_id'],
                'paid_date' => date('Y-m-d'),
                'payment_type' => $this->paymentType,
                'commission_per' => $this->commissionPer,
                'paid_amount' => $this->commission
            ];
            BlitznetCommission::createOrUpdateBlitznetCommission($stcData);
    }
}

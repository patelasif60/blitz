<?php

namespace App\Jobs;

use App\Http\Controllers\Payment\XenditController;
use App\Models\BlitznetCommission;
use App\Models\CommissionType;
use App\Models\Disbursements;
use App\Models\XenBalanceTransfer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class GroupCommissionJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $disbursementId;
    public $paymentType;
    public $commissionPer;
    public $commission;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($disbursementId,$commission,$paymentType=1,$commissionPer=null)
    {
        $this->disbursementId = $disbursementId;
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
        $disbursement = Disbursements::where('id',$this->disbursementId)->first(['order_id']);
        if (!empty($disbursement)) {
            $order = $disbursement->order()->first(['id', 'group_id', 'supplier_id']);
            $supplierId = $order->supplier_id;
            $totalDiscountCount = DB::table('blitznet_commissions')->where(['group_id'=> $order->group_id,'commission_type_id'=>CommissionType::GROUP_COMMISSION])->count();
            $btData = [
                'reference' => 'GBC-' . $order->group_id . '/D'. $this->disbursementId .'/'. ($totalDiscountCount + 1),
                'amount' => $this->commission,
                'source_user_id' => getXenPlatformIdBySupplierId($supplierId),
            ];
            $xendit = new XenditController;
            $result = $xendit->balanceTransfer($btData);
            $result['supplier_id'] = $supplierId;
            $xenBalResult = XenBalanceTransfer::createOrUpdateXenBalance($result);
            $stcData = [
                'group_id' => $order->group_id,
                'order_id' => $order->id,
                'supplier_id' => $supplierId,
                'disbursement_id' => $this->disbursementId,
                'xen_balance_transfer_id' => $xenBalResult->id,
                'commission_type_id' => CommissionType::GROUP_COMMISSION,
                'paid_date' => date('Y-m-d'),
                'payment_type' => $this->paymentType,
                'commission_per' => $this->commissionPer,
                'paid_amount' => $this->commission
            ];
            BlitznetCommission::createOrUpdateBlitznetCommission($stcData);
        }
    }
}

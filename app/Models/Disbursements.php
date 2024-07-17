<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\SystemActivities;

class Disbursements extends Model
{
    use HasFactory, SystemActivities;

    protected $fillable = ['batch_disbursement_id', 'group_id', 'order_id', 'buyer_user_id', 'disbursement_id', 'user_id', 'external_id', 'status', 'payment_type', 'disbursement_per', 'amount', 'bank_reference', 'bank_code', 'valid_name', 'bank_account_name', 'bank_account_number', 'disbursement_description', 'is_instant', 'failure_code', 'failure_message', 'email_to', 'email_cc', 'email_bcc', 'created', 'updated', 'created_at', 'updated_at'];

    protected $tagname = "Disbursements";


    public static function createOrUpdateDisbursement($data){
        $data['disbursement_id'] = $data['id'];
        unset($data['id']);
        if (isset($data['account_holder_name'])) {
            $data['bank_account_name'] = $data['account_holder_name'];
        }
        if (isset($data['description'])) {
            $data['disbursement_description'] = $data['description'];
        }
        if (isset($data['email_to'])) {
            $data['email_to'] = implode(',',$data['email_to']);
        }
        if (isset($data['email_cc'])) {
            $data['email_cc'] = implode(',',$data['email_cc']);
        }
        if (isset($data['email_bcc'])) {
            $data['email_bcc'] = implode(',',$data['email_bcc']);
        }

        $result = self::where(['disbursement_id'=>$data['disbursement_id'],'external_id'=>$data['external_id']])->first();
        if (is_null($result)) {
            return self::create($data);
        } else {
            $result->fill($data)->save();
            return $result;
        }
    }

    public function batchDisbursements()
    {
        return $this->belongsTo(BatchDisbursements::class);
    }
/*
 * Normal flow payable amount for disbursement
 * */
    public static function getPayableAmount($order,$data)
    {
        $quote = $order->quote()->first(['id','tax']);
        $quoteChargesWithAmounts = $order->quote->quoteChargesWithAmounts()->get(['id','addition_substraction','charge_amount','charge_type']);
        $platformCharges = $quoteChargesWithAmounts->where('charge_type',0)->values();
        $logisticCharges = $quoteChargesWithAmounts->where('charge_type',1)->whereIn('id',$data['logistic_charges']??[])->values();

        /*$transactionCharges = $order->quote->quotePaymentFees()->where('charge_id',10)->pluck('charge_amount')->first();*/

        $total = self::total(self::setTax($quote->tax,(float)$quote->quoteItems()->sum('product_amount')),$platformCharges,$quote->tax);
        if ($logisticCharges->count()) {
            $total = self::total($total, $logisticCharges, $quote->tax);
        }
        //if supplier not paid current month one time fee then it will deducted here
        /*$total = $total-$data['supplierTransactionFees'];

        $supplierDisbursementCharge = getDisbursementCharge();

        if ($transactionCharges<10450) {
            return round($total - $supplierDisbursementCharge);
        }*/
        return round($total);
    }
/*
 * Munir
 * get group final payable amount
 * Note:- if any changes on this page be careful, this code is used on GroupSupplierDisbursementRequest class,
 * */
    public static function getGroupPayableAmount($order,$data,$return=0)
    {
        $quote = $order->quote()->first(['id','group_id','tax']);
        $quoteChargesWithAmounts = $order->quote->quoteChargesWithAmounts()->get(['id','addition_substraction','charge_amount','charge_type']);
        $platformCharges = $quoteChargesWithAmounts->where('charge_type',0)->values();
        $logisticCharges = $quoteChargesWithAmounts->where('charge_type',1)->whereIn('id',$data['logistic_charges']??[])->values();

        $transactionCharges = $order->quote->quotePaymentFees()->where('charge_id',10)->pluck('charge_amount')->first();

        $totalProductAmount = (float)$quote->quoteItems()->sum('product_amount');
        $total = self::total(self::setTax($quote->tax,$totalProductAmount),$platformCharges,$quote->tax);
        if ($logisticCharges->count()) {
            $total = self::total($total, $logisticCharges, $quote->tax);
        }
        $maxDisbursementAmount = getMaxDisbursementAmountForGroup($quote->group()->first());
        $refundableAmount = $total-$maxDisbursementAmount;

        //blitznet commission calculation
        $blitznetCommission = 0;
        if (isset($data['blitznet_commission']) && $data['blitznet_commission']==1){
            if ($data['blitznet_commission_type']==1){//flat commission type
                $blitznetCommission = (int)$data['blitznet_commission_amount'];
            }else{//for percentage wise commission
                $data['blitznet_commission_per'] = (float)$data['blitznet_commission_per'];
                if ($data['blitznet_commission_per']>0 && $data['blitznet_commission_per']<=100) {
                    $blitznetCommission = self::setAmountByPercentage($total,(float)$data['blitznet_commission_per']);
                }
            }
        }
        $returnData['commission'] = $blitznetCommission;
        $total = $total-$blitznetCommission;
        //if supplier not paid current month one time fee then it will deducted here
        $total = $total-($data['supplier_transaction_fees']??0);

        $supplierDisbursementCharge = getDisbursementCharge();

        if ($transactionCharges<10450) {
            $total = $total - $supplierDisbursementCharge;
        }

        if ($refundableAmount>0){
            $total = $total-$refundableAmount;
        }
        $returnData['total'] = round($total);
        if ($return){
            return $returnData;
        }
        return $returnData['total'];
    }

    public static function total($total,$data,$tax=0)
    {
        foreach ($data as $raw){
            if ($raw->addition_substraction) {
                $total = $total + self::setTax($tax,(float)$raw->charge_amount);
            }else{
                $total = $total - self::setTax($tax,(float)$raw->charge_amount);
            }
        }
        return $total;
    }

    public static function setTax($tax,$amount)
    {
        return $amount+(($amount*$tax)/100);
    }

    public static function setAmountByPercentage($amount,$per)
    {
        return round(($amount*$per)/100);
    }

    public function order(){
        return $this->belongsTo(Order::class);
    }

    public function SupplierTransactionCharges()
    {
        return$this->hasMany(SupplierTransactionCharge::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\SystemActivities;

class OrderTransactions extends Model
{
    use HasFactory, SystemActivities;

    protected $tagname = "Order Transaction";
    protected $fillable = ['invoice_id', 'order_id', 'external_id', 'bulk_payment_id', 'user_id', 'status', 'merchant_name', 'merchant_profile_picture_url', 'amount', 'payer_email', 'expiry_date', 'invoice_url',
                            'should_send_email', 'success_redirect_url', 'failure_redirect_url', 'created', 'updated', 'currency', 'items', 'customer', 'payment_destination', 'bank_code', 'paid_amount',
                            'initial_amount', 'fees_paid_amount', 'adjusted_received_amount', 'payment_method', 'payment_channel', 'paid_at', 'credit_card_charge_id', 'description', 'created_at', 'updated_at'];

    public static function invoiceExist($orderId,$bulkPaymentId=0){
        if (empty($orderId)) {
            return self::where(['order_id' => $orderId])->where('status', '!=', 'EXPIRED')->count();
        }
        return self::where(['bulk_payment_id' => $bulkPaymentId])->where('status', '!=', 'EXPIRED')->count();
    }

    public static function createOrUpdateInvoice($data){
        $data['invoice_id'] = $data['id'];
        unset($data['id']);
        if (isset($data['customer'])) {
            $data['customer'] = json_encode($data['customer']);
        }
        if (isset($data['items'])) {
            $data['items'] = json_encode($data['items']);
        }
        $result = null;
        if (!isset($data['bulk_payment_id'])) {
            $result = self::where(['invoice_id' => $data['invoice_id'], 'order_id' => $data['order_id']])->where('status', '!=', 'EXPIRED')->first();
        }else{
            $result = self::where(['invoice_id' => $data['invoice_id'], 'bulk_payment_id' => $data['bulk_payment_id']])->where('status', '!=', 'EXPIRED')->first();
        }
        if (is_null($result)) {
            return self::create($data);
        } else {
            $result->fill($data)->save();
            return $result;
        }
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function bulkPayment()
    {
        return $this->belongsTo(BulkPayments::class,'bulk_payment_id');
    }

}

<?php

namespace App\Models;

use App\Traits\SystemActivities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupTransactions extends Model
{
    use HasFactory, SystemActivities;

    protected $fillable = ['invoice_id', 'group_id', 'quote_id', 'order_id', 'external_id', 'user_id', 'status', 'merchant_name', 'merchant_profile_picture_url', 'amount', 'payer_email', 'expiry_date',
        'invoice_url', 'should_send_email', 'success_redirect_url', 'failure_redirect_url', 'created', 'updated', 'currency', 'items', 'customer', 'payment_destination', 'bank_code', 'paid_amount',
        'initial_amount', 'fees_paid_amount', 'adjusted_received_amount', 'payment_method', 'payment_channel', 'paid_at', 'credit_card_charge_id', 'description', 'created_at', 'updated_at'];

    public static function invoiceExist($quoteId){
        return self::where(['quote_id' => $quoteId])->where('status', '!=', 'EXPIRED')->count();
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

        $result = self::where(['invoice_id' => $data['invoice_id'], 'quote_id' => $data['quote_id']])->where('status', '!=', 'EXPIRED')->first();
        if (is_null($result)) {
            return self::create($data);
        } else {
            $result->fill($data)->save();
            return $result;
        }
    }

    public function group()
    {
        return $this->belongsTo(Groups::class);
    }

    public function quote()
    {
        return $this->belongsTo(Quote::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

}

<?php

namespace App\Models;

use App\Traits\SystemActivities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlitznetCommission extends Model
{
    use HasFactory, SystemActivities;

    protected $fillable = ['group_id', 'order_id', 'supplier_id', 'disbursement_id', 'xen_balance_transfer_id', 'commission_type_id', 'payment_type', 'commission_per', 'paid_date', 'paid_amount', 'description', 'created_at', 'updated_at', 'deleted_at'];


    public static function createOrUpdateBlitznetCommission($data){
        $result = null;
        if (isset($data['id'])) {
            $result = self::where(['id' => $data['id']])->first();
        }
        if (is_null($result)) {
            return self::create($data);
        } else {
            $result->fill($data)->save();
            return $result;
        }
    }

    public function group(){
        return $this->belongsTo(Groups::class,'group_id');
    }

    public function order(){
        return $this->belongsTo(Order::class);
    }

    public function disbursement(){
        return $this->belongsTo(Disbursements::class);
    }

    public function supplier(){
        return $this->belongsTo(Supplier::class);
    }

    public function xenBalanceTransfer(){
        return $this->belongsTo(XenBalanceTransfer::class);
    }

    public function commissionType(){
        return $this->belongsTo(CommissionType::class);
    }

}

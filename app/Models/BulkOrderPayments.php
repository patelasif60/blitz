<?php

namespace App\Models;

use App\Traits\SystemActivities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BulkOrderPayments extends Model
{
    use SoftDeletes,HasFactory, SystemActivities;

    protected $fillable = ['bulk_payment_id', 'order_id', 'quote_id', 'rfq_id', 'discounted_amount', 'deleted_at', 'created_at', 'updated_at'];

    public static function createOrUpdateBulkOrderPayment($data){
        $result = self::where(['bulk_payment_id'=>$data['bulk_payment_id'],'order_id'=>$data['order_id']])->withTrashed()->first();
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
        return $this->belongsTo(BulkPayments::class,'bulk_payment_id','id');
    }


}

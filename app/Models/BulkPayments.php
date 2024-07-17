<?php

namespace App\Models;

use App\Traits\SystemActivities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class BulkPayments extends Model
{
    use SoftDeletes,HasFactory, SystemActivities;

    protected $fillable = ['bulk_payment_number', 'user_id','supplier_id', 'order_transaction_id', 'total_amount', 'total_discounted_amount', 'payable_amount', 'description', 'deleted_at', 'created_at', 'updated_at'];

    public static function createOrUpdateBulkPayment($data){
        $result = self::where(['bulk_payment_number'=>$data['bulk_payment_number'],'supplier_id'=>$data['supplier_id']])->withTrashed()->first();
        if (is_null($result)) {
            return self::create($data);
        } else {
            $result->fill($data)->save();
            return $result;
        }
    }

    public function bulkOrderPayment()
    {
        return $this->hasOne(BulkOrderPayments::class,'bulk_payment_id');
    }

    public function bulkOrderPayments()
    {
        return $this->hasMany(BulkOrderPayments::class,'bulk_payment_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function orderTransaction()
    {
        return $this->belongsTo(OrderTransactions::class);
    }
}

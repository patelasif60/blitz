<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\SystemActivities;
use Illuminate\Support\Facades\Auth;
use phpDocumentor\Reflection\Types\False_;

class OrderBatch extends Model
{
    use HasFactory, SystemActivities;

    protected $tagname = "Order Batches";
    protected $fillable = [
        'order_id',
        'airwaybill_id',
        'supplier_address_id',
        'user_address_id',
        'order_batch',
        'order_item_ids',
        'order_pickup',
        'created_by',
        'updated_by'
    ];

    // Create order batch to generate airwaybill number
    /*public static function createOrUpdateOrderBatch($orderBatchData) {
        $orderBatch = new OrderBatch();
        $orderBatch->order_id = $orderBatchData['order_id'];
        $orderBatch->airwaybill_id = NULL;
        $orderBatch->supplier_address_id = $orderBatchData['supplier_address_id'];
        $orderBatch->order_batch = 'BORN-'.$orderBatchData['order_id'].'/1';
        $orderBatch->order_item_ids = json_encode(array_map('intval', explode(',', $orderBatchData['order_item_ids'])));
        $orderBatch->order_pickup = $orderBatchData['pickup_datetime'];
        $orderBatch->created_by = Auth::user()->id;
        $orderBatch->updated_by = Auth::user()->id;
        $orderBatch->save();
        if($orderBatch->save() == true) {
            return response()->json(array('success' => TRUE, 'msg' => 'AirWayBill number generated successfully', 'batch_id' => $orderBatch->id));
        } else {
            return response()->json(array('success' => FALSE, 'msg' => 'Something went wrong !', 'batch_id' => ''));
        }
    }*/

    public static function createOrUpdateOrderBatch($data) {
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

    // Get Supplier address by address id
    public function getSupplierAddress() {
        return $this->hasOne(SupplierAddress::class,'id','supplier_address_id');
    }
    // Get User address by address id
    public function getUserAddress() {
        return $this->hasOne(UserAddresse::class,'id','user_address_id');
    }

    // Get Supplier address by address id
    public function getAirWayBillNumber() {
        return $this->belongsTo(AirWayBillNumber::class,'airwaybill_id');
    }

    // Update batch id in last inserted airwaybill number
    public static function updateorderBatchData($updateBatchIdData) {
        $result = self::where('id',$updateBatchIdData['batch_id'])->update(['airwaybill_id'=>$updateBatchIdData['awb_id']]);
        if($result == 1) {
            return response()->json(array('success' => true,'msg'=>__('admin.airwaybill_generated_successfully')));
        } else {
            return response()->json(array('success' => false,'msg'=>__('admin.something_error_message')));
        }
    }

    public function order() {
        return $this->belongsTo(Order::class);
    }
}

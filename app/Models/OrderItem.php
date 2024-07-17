<?php

namespace App\Models;

use App\Traits\SystemActivities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderItem extends Model
{
    use HasFactory, SoftDeletes, SystemActivities;

    protected $fillable = ["order_item_number", "order_id", "quote_item_id", "product_id", "rfq_product_id", "order_item_status_id",  "order_batch_id", "is_in_batch","product_amount", "min_delivery_date", "max_delivery_date", "order_latter","created_at", "updated_at", "deleted_at"];

    public static function createOrUpdateOrderItem($data){
        $result = null;
        if (isset($data['id'])) {
            $result = self::where(['id' => $data['id']])->first();
        }elseif(isset($data['order_id'])&&isset($data['quote_item_id'])&&isset($data['product_id'])&&isset($data['rfq_product_id'])) {
            $result = self::where(['order_id' => $data['order_id'],'quote_item_id' => $data['quote_item_id'],'product_id' => $data['product_id'],'rfq_product_id' => $data['rfq_product_id']])->withTrashed()->first();
        }
        if (is_null($result)) {
            return self::create($data);
        } else {
            $data['deleted_at'] = null;
            $result->fill($data)->save();
            return $result;
        }
    }
    public static function UpdateOrderDelivery($order_item_ids){
        $result = self::whereIn('id',$order_item_ids['orderItemsIds'])->update(['order_batch_id'=>$order_item_ids['batch_id']??null,'is_in_batch' => $order_item_ids['is_in_batch']??0]);
        return true;
    }
    public function quoteItem(){
        return $this->belongsTo(QuoteItem::class);
    }

    public function order(){
        return $this->belongsTo(Order::class);
    }

    public function orderItemStatus(){
        return $this->belongsTo(OrderItemStatus::class);
    }

    /**
     * Relationship b/n Product and Order Item
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    /**
     * Relationship b/n RFQ Product and Order Item
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function rfqProduct()
    {
        return $this->belongsTo(RfqProduct::class, 'rfq_product_id', 'id');
    }

    /**
     * Order Item Track relationship with Order Item
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orderItemTracks()
    {
        return $this->hasMany(OrderItemTracks::class,'order_item_id','id');
    }
    public function orderAirwayBillNumber(){
        return $this->hasOne(AirWayBillNumber::class,'order_batch_id','order_batch_id');
    }
}

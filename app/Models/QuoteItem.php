<?php

namespace App\Models;

use App\Traits\SystemActivities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Jenssegers\Mongodb\Eloquent\HybridRelations;

class QuoteItem extends Model
{
    use HasFactory, SoftDeletes,HybridRelations, SystemActivities;
    protected $connection = 'mysql';
    protected $fillable = ["rfq_product_id", "quote_item_number", "quote_id", "supplier_id", "product_id", "product_price_per_unit", "product_quantity", "price_unit", "product_amount", "min_delivery_days", "max_delivery_days", "supplier_final_amount", "supplier_tex_value", "logistic_check", "logistic_provided", "certificate", "weights", "dimensions", "length", "width", "height","pickup_service" ,"pickup_fleet","logistics_service_code","insurance_flag","wood_packing" ,"inclusive_tax_other","inclusive_tax_logistic","created_at", "updated_at", "deleted_at"];

    public static function createOrUpdateQuoteItem($data){
        $result = null;
        if (isset($data['id'])) {
            $result = self::where(['id' => $data['id']])->first();
        }elseif(isset($data['quote_id'])&&isset($data['rfq_product_id'])) {
            $result = self::where(['quote_id' => $data['quote_id'],'rfq_product_id' => $data['rfq_product_id']])->withTrashed()->first();
        }
        if (is_null($result)) {
            return self::create($data);
        } else {
            $data['deleted_at'] = null;
            $result->fill($data)->save();
            return $result;
        }
    }

    public function orderItem(){
        return $this->belongsTo(OrderItem::class, 'id', 'quote_item_id');
    }
    public function rfqProduct(){
        return $this->belongsTo(RfqProduct::class, 'rfq_product_id', 'id');
    }
    public function quoteDetails(){
        return $this->belongsTo(Quote::class, 'quote_id', 'id');
    }
    public function supplier(){
        return $this->belongsTo(Supplier::class);
    }
    public function unit(){
        return $this->belongsTo(Unit::class,'price_unit','id');
    }
    /*
    * Relation Beetween product and quote item
    * */
    public function product(){
        return $this->belongsTo(Product::class);
    }
    /*
    * Relation Beetween quoteItem and quote item
    * */
    public function quote(){
        return $this->belongsTo(Quote::class);
    }
}

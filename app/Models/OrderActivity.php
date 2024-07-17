<?php

namespace App\Models;

use App\Traits\SystemActivities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderActivity extends Model
{
    use HasFactory, SystemActivities;

    protected $fillable = ['order_id', 'order_item_id', 'user_id', 'key_name', 'old_value', 'new_value', 'user_type', 'is_deleted', 'created_at', 'updated_at'];

    public function user(){
        return $this->morphTo();
    }

    public static function createOrUpdateOrderActivity($data){
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

    public function order(){
        return $this->belongsTo(Order::class);
    }

    public function orderItem(){
        return $this->belongsTo(OrderItem::class);
    }

}

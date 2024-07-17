<?php

namespace App\Models;

use App\Traits\SystemActivities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItemTracks extends Model
{
    use HasFactory, SystemActivities;

    protected $fillable = [
        'order_id', 'order_item_id', 'status_id', 'user_id', 'created_at', 'updated_at'
    ];

    public static function createOrUpdateOrderItemTrack($data){
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

    public function orderItemStatus(){
        return $this->hasMany(OrderItemStatus::class,'id','status_id');
    }
}

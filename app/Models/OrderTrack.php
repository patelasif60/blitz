<?php

namespace App\Models;

use App\Traits\SystemActivities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderTrack extends Model
{
    use HasFactory, SystemActivities;

    protected $fillable = [
        'order_id', 'status_id', 'user_id', 'user_type', 'is_deleted', 'created_at', 'updated_at'
    ];

    public static function createOrUpdateOrderTrack($data){
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

}

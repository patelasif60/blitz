<?php

namespace App\Models;

use App\Traits\SystemActivities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class XenBalanceTransfer extends Model
{
    use HasFactory, SystemActivities;

    protected $fillable = ['supplier_id', 'transfer_id', 'reference', 'source_user_id', 'destination_user_id', 'status', 'amount', 'created', 'created_at', 'updated_at'];

    public static function createOrUpdateXenBalance($data){
        $result = self::where(['supplier_id'=>$data['supplier_id'],'transfer_id'=>$data['transfer_id']])->first();
        if (is_null($result)) {
            return self::create($data);
        } else {
            $result->fill($data)->save();
            return $result;
        }
    }

}

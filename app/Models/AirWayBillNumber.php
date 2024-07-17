<?php

namespace App\Models;

use App\Traits\SystemActivities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AirWayBillNumber extends Model
{
    use HasFactory, SystemActivities;
    protected $table = 'airwaybill_number';
    protected $fillable = [
        'order_batch_id',
        'order_id',
        'airwaybill_number',
        'airwaybill_status',
        'created_at',
        'updated_at'
    ];


    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at','updated_at'];

    //Update batch id in last inserted airwaybill number
    public static function updateAwbData($updateBatchIdData){
        $result = self::where('id',$updateBatchIdData['awb_id'])->update(['order_batch_id'=>$updateBatchIdData['batch_id']]);
        return true;
    }
}

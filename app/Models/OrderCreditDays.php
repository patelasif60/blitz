<?php

namespace App\Models;

use App\Traits\SystemActivities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderCreditDays extends Model
{
    use HasFactory, SystemActivities;

    public static function saveOrderCreditDayStatus($orderId,$selectedStatusID){
        $ocd = self::where('order_id',$orderId)->first();
        if ($selectedStatusID==9){
            $ocd->status = 1;
        }elseif($selectedStatusID==10){
            $ocd->status = 2;
        }
        $ocd->save();
    }

}

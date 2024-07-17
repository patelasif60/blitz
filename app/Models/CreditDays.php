<?php

namespace App\Models;

use App\Traits\SystemActivities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CreditDays extends Model
{
    use HasFactory, SystemActivities;

    public static function getAllActiveCreditDays($select='id,name,days,description'){
        return DB::table('credit_days')->selectRaw($select)->where(['status'=>1,'is_deleted'=>0])->orderBy('sort')->get()->toArray();
    }

    public static function getActiveCreditDaysbyId($id,$where='1',$select='id,name,days,description'){
        return DB::table('credit_days')->selectRaw($select)->where(['id'=>$id,'status'=>1,'is_deleted'=>0])->whereRaw($where)->first();
    }
}

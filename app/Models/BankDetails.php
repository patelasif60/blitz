<?php

namespace App\Models;

use App\Traits\SystemActivities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class BankDetails extends Model
{
    use HasFactory, SystemActivities;

    public static function getBankDetails($select="id,bank_name,ac_name,ac_no,bank_code,description"){
        return DB::table('bank_details')->selectRaw($select)->where('status',1)->first();
    }
}

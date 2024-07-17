<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\BuyerBanks;
use App\Traits\SystemActivities;

class AvailableBank extends Model
{
    use HasFactory, SystemActivities;
    protected $tagname = "Available Banks";

    protected $fillable = ['name', 'code', 'logo', 'can_disburse', 'can_name_validate', 'deleted_at', 'created_at', 'updated_at'];


    public static function createOrUpdateBank($data){

        $result = self::where(['code'=>$data['code']])->first();
        if (is_null($result)) {
            return self::create($data);
        } else {
            $result->fill($data)->save();
            return $result;
        }
        /**begin: system log**/
        AvailableBank::bootSystemActivities();
        /**end:  system log**/
    }

    /**
     * Get Buyer Banks by available bank id
     *
     * @return mixed
     */
    public function BuyerBanks() {
        return $this->hasMany(BuyerBanks::class,'bank_id','id');
    }

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\SystemActivities;

class SupplierTransactionCharge extends Model
{
    use HasFactory, SystemActivities;

    protected $fillable = ['supplier_id', 'disbursement_id', 'xen_transfer_id', 'paid_date', 'paid_amount', 'created_at', 'updated_at'];

    protected $tagname = "Supplier Tranjaction Charges";


    public static function createOrUpdateCharge($data){
        $result = self::where(['supplier_id'=>$data['supplier_id'],'paid_date'=>date('Y-m-d')])->first();
        if (is_null($result)) {
            return self::create($data);
        } else {
            $result->fill($data)->save();
            return $result;
        }
    }

    public function supplier(){
        return $this->belongsTo(Supplier::class);
    }

    public function disbursement(){
        return $this->belongsTo(Disbursements::class);
    }

}

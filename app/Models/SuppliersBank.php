<?php

namespace App\Models;

use App\Traits\SystemActivities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class SuppliersBank extends Model
{
    use HasFactory, SystemActivities;

    use SoftDeletes;

    protected $fillable = ['bank_id', 'supplier_id', 'is_primary', 'bank_account_name', 'bank_account_number', 'description', 'deleted_at', 'created_at', 'updated_at'];

    protected $dates = ['deleted_at'];

    public static function createOrUpdateSuppliersBank($data,$id=0){
        if (!isset($data['is_primary'])){
            $data['is_primary'] = 0;
        }
        if ($data['is_primary']){
            self::where(['supplier_id' => $data['supplier_id']])->update(['is_primary'=>0]);
        }
        if ($id){
            $result = self::where(['id' => $id, 'supplier_id' => $data['supplier_id']])->first();
        }else {
            $result = self::where(['supplier_id' => $data['supplier_id'], 'bank_id' => $data['bank_id'], 'bank_account_number' => $data['bank_account_number']])->first();
        }
        if (is_null($result)) {
            return self::create($data);
        } else {
            $result->fill($data)->save();
            return $result->id;
        }
    }

    public static function getSupplierBank($id)
    {
        return DB::table('suppliers_banks')
            ->where('suppliers_banks.id', $id)
            ->join('available_banks', 'suppliers_banks.bank_id', '=', 'available_banks.id')
            ->first(['suppliers_banks.*', 'available_banks.name', 'available_banks.code', 'available_banks.logo']);
    }

    public function supplier(){
        return $this->belongsTo(Supplier::class);
    }

    public function bankDetail()
    {
        return $this->belongsTo(AvailableBank::class,'bank_id');
    }
}

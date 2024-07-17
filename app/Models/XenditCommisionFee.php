<?php

namespace App\Models;

use App\Traits\SystemActivities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class XenditCommisionFee extends Model
{
    use HasFactory, SystemActivities;
    protected $fillable = ['charge_id', 'company_id', 'type', 'charges_value', 'charges_type', 'charges_type', 'addition_substraction', 'is_delete','created_at'];

    public function company()
    {
        return $this->belongsTo(Company::class,'company_id');
    }

    public function charges()
    {
        return $this->hasMany(OtherCharge::class);
    }
}

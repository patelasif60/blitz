<?php

namespace App\Models;

use App\Traits\SystemActivities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class LoanProviderCharges extends Model
{
    use HasFactory,SoftDeletes, SystemActivities;

    const INTERNAL_CHARGES = 3;

    protected $fillable = [
        'id',
        'loan_provider_id',
        'charges_type_id',
        'amount_type',
        'addition_substraction',
        'value',
        'period_in_days',
        'period_in_month'
    ];

    /**
     * day_charge_desc attribute
     *
     * @return string
     */
    public function getDayChargeDescAttribute()
    {
        return $this->amount_type == 0 ? $this->value.'% '.__('admin.for').' '.$this->period_in_days.' '.__('admin.days') : '';

    }

    /**
     * month_charge_desc attribute
     *
     * @return string
     */
    public function getMonthChargeDescAttribute()
    {
        return $this->account_type == 0 ? $this->value.' %'.__('admin.for').' '.$this->period_in_month.' '.__('admin.month') : '';

    }

    /**
     * loan provider charges type relationship
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function loanProviderChargesType(){
        return $this->belongsTo(LoanProviderChargesType::class,'charges_type_id');
    }

    /**
     * get all active loan provider charges
     * @return object
     */
    public static function getAllLoanProviderCharges(){
        return self::where('loan_provider_id',LOAN_PROVIDERS['KOINWORKS'])
            ->whereHas('loanProviderChargesType',function ($q){
                $q->where('status',ACTIVE_STATUS);
            })->get(['id','charges_type_id','amount_type','addition_substraction','period_in_days','period_in_month','value']);
    }

}

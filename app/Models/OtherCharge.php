<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\SystemActivities;


class OtherCharge extends Model
{
    use HasFactory, SystemActivities;

    const XENDIT = 10;

    protected $tagname = "Other Charges";

    protected $fillable = [
        'name','description',
        'type',
        'charges_value',
        'value_on',
        'charges_type',
        'status',
        'addition_substraction','is_deleted','added_by','updated_by','deleted_by',
        'editable'
    ];


    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at','updated_at'];
    public function trackAddData()
    {
        return $this->hasOne(User::class,'id','added_by');
    }
    public function trackUpdateData()
    {
        return $this->hasOne(User::class,'id','updated_by');
    }

    public function company()
    {
        return $this->belongsTo(XenditCommisionFee::class,'id', 'charge_id');
    }

    public function quoteChargeWithAmount()
    {
        return $this->belongsTo(QuoteChargeWithAmount::class,'id', 'charge_id');
    }
    /*
     * @ekta
     * relation used for get data from xendit commisionfee
     */
    public function xenditCommisionFee()
    {
        return $this->belongsTo(XenditCommisionFee::class,'id', 'charge_id');
    }
}

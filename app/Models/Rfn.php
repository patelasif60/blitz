<?php

namespace App\Models;

use App\Traits\SystemActivities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class Rfn extends Model
{
    use HasFactory, SystemActivities, SoftDeletes;

    const RFN = 1;
    const GLOBALRFN = 2;
    const PENDING_STATUS = 1;
    const APPROVED_STATUS = 2;
    const RFNPREFIX = 'BRFN';
    const GRFNPREFIX = 'BGRFN';

    protected $fillable = [
        'user_type',
        'user_id',
        'company_id',
        'rfq_id',
        'prefix',
        'rfn_number',
        'type',
        'comment',
        'is_converted',
        'expected_date',
        'start_date',
        'end_date',
        'status'
    ];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();
        static::created(function ($rfn) {
            $rfn->setReferenceNumber($rfn);
        });
    }

    /**
     * Update reference number
     *
     * @param Rfn $rfn
     */
    protected function setReferenceNumber(Rfn $rfn)
    {
        if ($rfn->type == Rfn::RFN) {
            $rfn->prefix = Rfn::RFNPREFIX;
        } else {
            $rfn->prefix = Rfn::GRFNPREFIX;
        }
        $rfn->rfn_number = $rfn->id;
        $rfn->save();
    }

    /**
     * Get the reference_number
     *
     * @return string
     */
    public function getReferenceNumberAttribute()
    {
        return $this->prefix.'-'.$this->rfn_number;
    }

    /**
     * Get the remaining_days
     *
     * @return string
     */
    public function getRemainingDaysAttribute()
    {
        $now = \Carbon\Carbon::today()->format('Y-m-d');
        $start_date = \Carbon\Carbon::parse($this->start_date)->format('Y-m-d');
        $end_date = \Carbon\Carbon::parse($this->end_date)->format('Y-m-d');
        if ($start_date >= $now && $end_date >=$now)
        {
            //start ane end date is Greater than and equal to now date
            return (!empty($this->start_date) && ($this->end_date) ? \Carbon\Carbon::parse($start_date)->diff($end_date,false)->days +  1: 0);
        }elseif($start_date < $now && $end_date < $now){
            // start date and end date is less than now  so 0 days remaining
            return 0;
        }elseif ($end_date <= $now){
            // end date is same as now date
            return (!empty($this->start_date) && ($this->end_date) ? \Carbon\Carbon::now()->diff($end_date,false)->days + 1: 0);
        }
        else{
            // Now date is in between start and end date
            return (!empty($this->start_date) && ($this->end_date) ? \Carbon\Carbon::now()->diff($end_date,false)->days + 2 : 0);
        }
    }

    /**
     * Get the status_name
     *
     * @return array|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Translation\Translator|string|null
     */
    public function getStatusNameAttribute()
    {
        return ($this->status == 1 ? __('buyer.pending') : ($this->status == 2 ? __('buyer.approved') : ($this->status == 3 ? __('buyer.cancelled') :'')));
    }

    /**
     * Get the status_color
     *
     * @return array|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Translation\Translator|string|null
     */
    public function getStatusColorAttribute()
    {
        return ($this->status == 1 ? 'bg-primary' : ($this->status == 2 ? 'bg-success' : ($this->status == 3 ? 'bg-danger' :'')));
    }

    /**
     * Get Rfq of Rfn
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function rfq()
    {
        return $this->hasOne(Rfq::class,'rfq_id','id');
    }
    /**
     * Get rfnItem of Rfn
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function rfnItem()
    {
        return $this->hasOne(RfnItems::class,'rfn_id','id');
    }

    /**
     * Get rfnItems of Rfn
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function rfnItems()
    {
        return $this->hasMany(RfnItems::class,'rfn_id','id');
    }

    /**
     * Get Company of Rfn
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function company()
    {
        return $this->hasOne(Company::class,'company_id','id');
    }

    /**
     * Get rfnResponse of Rfn
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function rfnResponses()
    {
        return $this->hasMany(RfnResponse::class,'rfn_id','id');
    }

    /**
     * Get All strictly  used for strictResponses count
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function strictResponses()
    {
        return $this->hasMany(RfnResponse::class,'rfn_id','id');
    }
    /**
     * Get the parent userable model (user or supplier).
     */
    public function userable()
    {
        return $this->morphTo('userable','user_type','user_id');
    }

    /**
     * Get list of all global rfns
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
     public function getAllGlobalRfnList()
     {

        return Rfn::with('rfnItem','rfnItems','userable','rfnItem.product','rfnItem.unit','rfnResponses','rfnResponses.defaultCompanyUser','rfnResponses.rfnItem','rfnResponses.rfnItem.unit');

     }

    /**
     * Get User Name of Rfn
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function defaultCompanyUser()
    {
        return $this->hasMany(CompanyUser::class,'users_id','user_id')->where('company_id',Auth::user()->default_company);

    }
}

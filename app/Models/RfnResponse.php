<?php

namespace App\Models;

use App\Traits\SystemActivities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class RfnResponse extends Model
{
    use HasFactory, SystemActivities, SoftDeletes;

    protected $fillable = [
        'rfn_id',
        'user_type',
        'user_id',
        'company_id',
        'rfq_id',
        'expected_date',
        'status'
    ];
    /**
     * Get User Name of Rfn
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function defaultCompanyUser()
    {
        return $this->hasMany(CompanyUser::class,'users_id','user_id')->where('company_id',Auth::user()->default_company);

    }
     /**
     * Get User Name of Rfn
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     */

    public function rfnItem()
    {
        return $this->hasOne(RfnItems::class,'rfn_response_id','id');
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
     * Get Rfn
     */
    public function rfn()
    {
        return $this->hasOne(Rfn::class,'rfn_id','id');
    }
}

<?php

namespace App\Models;

use App\Traits\SystemActivities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * LoanApplicantAddress Table
 */
class LoanApplicantAddress extends Model
{
    use HasFactory, SystemActivities;

    const HOME_OWNERSHIP_STATUS = [
        1=>"FAMILY/KELUARGA",2=>"PARENT/ORANG TUA",3=>"RENTAL/KOS",4=>"OWNED/MILIK SENDIRI",5=>"OFFICE RESIDENCE/RUMAH DINAS"
    ];

    /**
     * fillable The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'applicant_id',
        'name',
        'address_line1',
        'address_line2',
        'postal_code',
        'sub_district',
        'district',
        'city_id',
        'other_city',
        'provinces_id',
        'other_provinces',
        'country_id',
        'has_live_here',
        'home_ownership_status',
        'duration_of_stay',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $appends = ['home_ownership_status_name'];

    public function getHomeOwnershipStatusNameAttribute()
    {
        return self::HOME_OWNERSHIP_STATUS[$this->home_ownership_status];
    }

    /**
     * applicant ha many address
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function applicant(){
        return $this->belongsTo(LoanApplicant::class);
    }

    /**
     * Get the city associated with the loan applicant address
     */
    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    /**
     * Get the state associated with the loan applicant address
     */
    public function state()
    {
        return $this->belongsTo(State::class, 'provinces_id');

    }

    /**
     * Get the country associated with the loan applicant address
     */
    public function country()
    {
        return $this->belongsTo(CountryOne::class, 'country_id');
    }

}

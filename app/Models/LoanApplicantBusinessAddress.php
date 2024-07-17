<?php

namespace App\Models;

use App\Traits\SystemActivities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanApplicantBusinessAddress extends Model
{
    use HasFactory, SystemActivities;
        /**
     * fillable The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'applicant_id',
        'applicant_business_id',
        'name',
        'address1',
        'address2',
        'district',
        'sub_district',
        'city_id',
        'other_city',
        'provinces_id',
        'other_provinces',
        'country_id',
        'postal_code',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
    /**
     * applicant ha many address
     *
     * @return void
     */

    public function applicant(){
        return $this->belongsTo(LoanApplicant::class);
    }
}

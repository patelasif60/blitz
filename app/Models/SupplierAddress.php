<?php

namespace App\Models;

use App\Traits\SystemActivities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierAddress extends Model
{
    use HasFactory, SystemActivities;

    protected $table = 'supplier_addresses';

    protected $fillable = [
        'supplier_id',
        'address_name',
        'address_line_1',
        'address_line_2',
        'pincode',
        'city',
        'state',
        'sub_district',
        'district',
        'default_address',
        'is_deleted',
        'created_at',
        'updated_at',
        'city_id',
        'state_id',
        'country_id',
        'country_one_id'
    ];

    protected $dates = ['created_at','updated_at'];

    /**
     * Get the city associated with the user RFQ.
     */
    public function getCity()
    {
        return $this->belongsTo(City::class, 'city_id', 'id')->first();
    }

    /**
     * Get the state associated with the user RFQ.
     */
    public function getState()
    {
        return $this->belongsTo(State::class, 'state_id', 'id')->first();

    }

    /**
     * Get the country associated with the user RFQ.
     */
    public function getCountryOne()
    {
        return $this->belongsTo(CountryOne::class, 'country_one_id', 'id')->first();
    }

}

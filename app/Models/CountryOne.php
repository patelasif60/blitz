<?php

namespace App\Models;

use App\Traits\SystemActivities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CountryOne extends Model
{
    use HasFactory, SoftDeletes, SystemActivities;

    const DEFAULTCOUNTRY = 102;

    protected $fillable = [
        'name',
        'iso2',
        'created_by',
        'updated_by'
    ];

    /**
     * Get the RFQ associated with the country (country one).
     */
    public function Rfq()
    {
        return $this->belongsTo(Rfq::class, 'country_one_id');
    }

    /**
     * Get the user addresses associated with the country (country one).
     */
    public function userAddress()
    {
        return $this->belongsTo(UserAddresse::class, 'country_one_id');
    }

    /**
     * Get the user addresses associated with the City.
     */
    public function states()
    {
        return $this->hasMany(State::class, 'country_id', 'id');
    }
}

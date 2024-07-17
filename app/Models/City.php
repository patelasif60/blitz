<?php

namespace App\Models;

use App\Traits\SystemActivities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class City extends Model
{
    use HasFactory, SoftDeletes, SystemActivities;

    protected $fillable = [
        'name',
        'country_id',
        'state_id',
        'created_by',
        'updated_by'
    ];

    /**
     * Get the RFQ associated with the city.
     */
    public function Rfq()
    {
        return $this->belongsTo(Rfq::class, 'city_id');
    }

    /**
     * Get the user addresses associated with the City.
     */
    public function userAddress()
    {
        return $this->belongsTo(UserAddresse::class, 'city_id');
    }


}

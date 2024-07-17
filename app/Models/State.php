<?php

namespace App\Models;

use App\Traits\SystemActivities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class State extends Model
{
    use HasFactory, SoftDeletes, SystemActivities;

    protected $fillable = [
      'name',
      'iso2',
      'country_id',
      'type',
      'latitude',
      'longitude',
      'created_by',
      'updated_by',
    ];

    /**
     * Get the RFQ associated with the state.
     */
    public function Rfq()
    {
        return $this->belongsTo(Rfq::class, 'state_id');
    }

    /**
     * Get the user addresses associated with the state.
     */
    public function userAddress()
    {
        return $this->hasMany(UserAddresse::class, 'state_id');
    }
}

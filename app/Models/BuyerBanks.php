<?php

namespace App\Models;

use App\Models\AvailableBank;
use App\Traits\SystemActivities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BuyerBanks extends Model
{
    use HasFactory, SoftDeletes, SystemActivities;

    const PRIMARY       = 1;
    const NOTPRIMARY    = 0;

    protected $fillable = [
        'user_id',
        'bank_id',
        'account_holder_name',
        'account_number',
        'description',
        'is_primary',
        'created_by',
        'updated_by',
        'is_user_primary',
        'user_type',
        'company_id'
    ];

    /**
     * Equolent relationship b/n AvailableBanks and BuyerBanks
     *
     * @return Model|\Illuminate\Database\Eloquent\Relations\BelongsTo|object|null
     */
    public function AvailableBanks() {

        return $this->belongsTo(AvailableBank::class,'bank_id','id');

    }

    /**
     * Get first data by equolent relationship b/n AvailableBanks and BuyerBanks
     *
     * @return Model|\Illuminate\Database\Eloquent\Relations\BelongsTo|object|null
     */
    public function getAvailableBank() {

        return $this->belongsTo(AvailableBank::class,'bank_id','id')->first();

    }

}

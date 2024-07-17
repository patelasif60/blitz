<?php

namespace App\Models;

use App\Traits\SystemActivities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class LoanProviderChargesType extends Model
{
    use HasFactory, SoftDeletes, SystemActivities;

    const LATE_FEE = 6;

    protected $fillable = [
        'loan_provider_id',
        'name',
        'description',
        'interest_rate_is_by_buyer',
        'status'
    ];
}

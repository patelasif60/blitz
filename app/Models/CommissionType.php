<?php

namespace App\Models;

use App\Traits\SystemActivities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommissionType extends Model
{
    use HasFactory, SystemActivities;

    const GROUP_COMMISSION = 1;
    const BLITZNET_COMMISSION = 2;
}

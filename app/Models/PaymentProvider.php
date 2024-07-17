<?php

namespace App\Models;

use App\Traits\SystemActivities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class PaymentProvider extends Model
{
    use HasFactory, SoftDeletes, SystemActivities;

    const XENDIT = 1;
}

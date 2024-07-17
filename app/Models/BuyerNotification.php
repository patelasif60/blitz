<?php

namespace App\Models;

use App\Traits\SystemActivities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BuyerNotification extends Model
{
    use HasFactory, SoftDeletes, SystemActivities;
    protected $fillable = [
        'user_activity',
        'translation_key',
        'is_show',
        'is_multiple_show',
        'notification_type',
        'notification_type_id'
    ];
}

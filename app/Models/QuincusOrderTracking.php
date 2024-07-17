<?php

namespace App\Models;

use App\Traits\SystemActivities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuincusOrderTracking extends Model
{
    use HasFactory, SystemActivities;

    protected $table = 'quincus_order_tracking';

    protected $fillable = [
        'order_id',
        'airwaybill_number',
        'blitznet_status_id',
        'quincus_status_code',
        'process_status',
        'quincus_status_description',
        'quincus_status_stage',
        'process_datetime',
        'process_location',
        'process_signature',
        'process_photo',
        'process_latitude',
        'process_longitude',
        'process_maps_location',
        'process_received_by',
        'process_received_relation',

    ];


}

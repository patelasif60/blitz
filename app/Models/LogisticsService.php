<?php

namespace App\Models;

use App\Traits\SystemActivities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogisticsService extends Model
{
    use HasFactory, SystemActivities;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'logistics_provider_id',
        'service_code',
        'service_name',
        'service_description',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
}

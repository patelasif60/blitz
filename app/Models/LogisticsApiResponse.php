<?php

namespace App\Models;

use App\Traits\SystemActivities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogisticsApiResponse extends Model
{
    use HasFactory, SystemActivities;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'logistics_provider_id',
        'user_id',
        'request_data',
        'response_code',
        'response_data',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
}

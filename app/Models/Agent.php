<?php

namespace App\Models;

use App\Traits\SystemActivities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Agent extends Model
{
    use HasFactory, SoftDeletes, SystemActivities;

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'email',
        'phone_code',
        'mobile',
        'profile_pic'
    ];
}

<?php

namespace App\Models;

use App\Traits\SystemActivities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserApprovalConfig extends Model
{
    use HasFactory, SystemActivities;
    protected $table = 'user_approval_configs';

    protected $fillable = [
        'user_id',
        'user_type',
        'is_deleted',
        'created_at',
        'updated_at',
    ];
    protected $dates = ['created_at','updated_at'];


}
